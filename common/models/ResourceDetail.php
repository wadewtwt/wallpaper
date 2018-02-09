<?php

namespace common\models;

use common\models\base\Enum;
use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "resource_detail".
 *
 * @property integer $id
 * @property integer $resource_id
 * @property integer $type
 * @property integer $container_id
 * @property string $tag_active
 * @property string $tag_passive
 * @property integer $is_online
 * @property integer $online_change_at
 * @property integer $maintenance_at
 * @property integer $scrap_at
 * @property integer $quantity
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Container $container
 * @property \common\models\Resource $resource
 * @property ResourceDetailOperation[] $resourceDetailOperations
 */
class ResourceDetail extends \common\models\base\ActiveRecord
{
    const STATUS_NORMAL = 0; // 正常在库
    const STATUS_OUTPUT = 10; // 已出库
    const STATUS_PICKED = 20; // 被领走

    public static $statusData = [
        self::STATUS_NORMAL => '正常',
        self::STATUS_OUTPUT => '已出库',
        self::STATUS_PICKED => '被领走',
    ];

    // 任然有用的状态
    public static $usedStatusData = [
        self::STATUS_NORMAL, self::STATUS_PICKED
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resource_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resource_id', 'type', 'container_id', 'tag_active', 'tag_passive', 'online_change_at', 'maintenance_at', 'scrap_at', 'quantity'], 'required'],
            [['resource_id', 'type', 'container_id', 'is_online', 'online_change_at', 'maintenance_at', 'scrap_at', 'quantity', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['tag_active', 'tag_passive'], 'string', 'max' => 255],
            [['container_id'], 'exist', 'skipOnError' => true, 'targetClass' => Container::className(), 'targetAttribute' => ['container_id' => 'id']],
            [['resource_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resource::className(), 'targetAttribute' => ['resource_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resource_id' => '资源 ID',
            'type' => '类型:消耗品、设备',
            'container_id' => '货位 ID',
            'tag_active' => '有源标签',
            'tag_passive' => '无源标签',
            'is_online' => '是否在线',
            'online_change_at' => '在线离线时间',
            'maintenance_at' => '最近维护时间',
            'scrap_at' => '报废时间',
            'quantity' => '数量',
            'status' => '状态',
            'created_at' => '创建时间',
            'created_by' => '创建人',
            'updated_at' => '修改时间',
            'updated_by' => '修改人',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContainer()
    {
        return $this->hasOne(Container::className(), ['id' => 'container_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResource()
    {
        return $this->hasOne(Resource::className(), ['id' => 'resource_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResourceDetailOperations()
    {
        return $this->hasMany(ResourceDetailOperation::className(), ['resource_detail_id' => 'id']);
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        return $this->toName($this->status, self::$statusData);
    }

    /**
     * @param $applyOrderType
     * @param \common\models\Resource $resource
     * @param bool $isMaintenance 是否是维护
     * @throws Exception
     */
    protected function changeStatusByApplyOrderType($applyOrderType, $resource, $isMaintenance = false)
    {
        if ($this->type == Resource::TYPE_DEVICE) {
            if ($applyOrderType == Enum::APPLY_ORDER_TYPE_OUTPUT) {
                $this->status = self::STATUS_OUTPUT;
            } elseif ($applyOrderType == Enum::APPLY_ORDER_TYPE_APPLY) {
                $this->status = self::STATUS_PICKED;
            } elseif (in_array($applyOrderType, [Enum::APPLY_ORDER_TYPE_RETURN, Enum::APPLY_ORDER_TYPE_INPUT])) {
                $this->status = self::STATUS_NORMAL;
            } else {
                throw new Exception('未知的 applyOrderType');
            }
        } elseif ($this->type == Resource::TYPE_EXPENDABLE) {
            if (in_array($applyOrderType, [Enum::APPLY_ORDER_TYPE_OUTPUT, Enum::APPLY_ORDER_TYPE_APPLY])) {
                $this->status = self::STATUS_OUTPUT;
            } elseif (in_array($applyOrderType, [Enum::APPLY_ORDER_TYPE_RETURN, Enum::APPLY_ORDER_TYPE_INPUT])) {
                $this->status = self::STATUS_NORMAL;
            } else {
                throw new Exception('未知的 applyOrderType');
            }
        } else {
            throw new Exception('未知的 type');
        }
        if (in_array($applyOrderType, [Enum::APPLY_ORDER_TYPE_OUTPUT, Enum::APPLY_ORDER_TYPE_APPLY])) {
            $this->is_online = 0;
            $this->online_change_at = time();
        } elseif (in_array($applyOrderType, [Enum::APPLY_ORDER_TYPE_RETURN, Enum::APPLY_ORDER_TYPE_INPUT])) {
            $this->is_online = 1;
            $this->online_change_at = time();
            if ($applyOrderType == Enum::APPLY_ORDER_TYPE_INPUT) {
                $this->scrap_at = time() + ($resource->scrap_cycle * 86400);
                $this->maintenance_at = time() + ($resource->maintenance_cycle * 86400);
            }
            if ($applyOrderType == Enum::APPLY_ORDER_TYPE_RETURN && $isMaintenance) {
                $this->maintenance_at = time() + ($resource->maintenance_cycle * 86400);
            }
        } else {
            throw new Exception('未知的 applyOrderType');
        }
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->toName($this->type, Resource::$typeData);
    }

    /**
     * 触发报警
     * @param bool $isActive 是否是有源报警
     * @throws \yii\db\Exception
     */
    public function triggerAlarm($isActive)
    {
        if ($this->status == static::STATUS_NORMAL) {
            /** @var AlarmConfig[] $alarmConfigs */
            $alarmConfigs = AlarmConfig::find()->andWhere([
                'status' => AlarmConfig::STATUS_NORMAL,
                'type' => AlarmConfig::TYPE_ILLEGAL_OUTPUT,
                'store_id' => $this->container->store_id,
            ])->all();
            foreach ($alarmConfigs as $alarmConfig) {
                if ($isActive) {
                    $desTemp = AlarmRecord::DES_TEMP_ILLEGAL_OUTPUT_ACTIVE;
                    $tag = $this->tag_active;
                } else {
                    $desTemp = AlarmRecord::DES_TEMP_ILLEGAL_OUTPUT_PASSIVE;
                    $tag = $this->tag_passive;
                }
                AlarmRecord::createOne($alarmConfig, $desTemp, [
                    'resourceName' => $this->resource->name,
                    'tag' => $tag,
                ], true);
            }
        }
    }

    /**
     * 有源标签是否被使用
     * @param $tag
     * @return bool
     */
    public static function isTagActiveUsed($tag)
    {
        $isExist = static::find()->select(['id'])
            ->andWhere(['tag_active' => $tag, 'status' => static::$usedStatusData])
            ->one();
        return (bool)$isExist;
    }

    /**
     * 无源标签是否被使用
     * @param $tag
     * @return bool
     */
    public static function isTagPassiveUsed($tag)
    {
        $isExist = static::find()->select(['id'])
            ->andWhere(['tag_passive' => $tag, 'status' => static::$usedStatusData])
            ->one();
        return (bool)$isExist;
    }

    /**
     * 创建一次设备操作
     * @param $applyOrderType
     * @param $applyOrderResource ApplyOrderResource
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public static function operateByApplyOrderType($applyOrderType, $applyOrderResource)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $isTagPassiveExist = static::isTagPassiveUsed($applyOrderResource->tag_passive);
            $resource = $applyOrderResource->resource;
            // 修改资源信息
            if ($applyOrderType == Enum::APPLY_ORDER_TYPE_INPUT) {
                $isTagActiveExist = static::isTagActiveUsed($applyOrderResource->tag_active);
                if ($isTagActiveExist) {
                    throw new Exception("有源标签为'{$applyOrderResource->tag_active}'的资源已存在");
                }
                if ($isTagPassiveExist) {
                    throw new Exception("无源标签为'{$applyOrderResource->tag_passive}'的资源已存在");
                }
                $model = new static();
                $model->resource_id = $resource->id;
                $model->type = $resource->type;
                $model->container_id = $applyOrderResource->container_id;
                $model->tag_active = $applyOrderResource->tag_active;
                $model->tag_passive = $applyOrderResource->tag_passive;
                $model->quantity = $applyOrderResource->quantity;
                $model->changeStatusByApplyOrderType($applyOrderType, $resource, false);
                $model->save(false);
            } elseif (in_array($applyOrderType, [
                Enum::APPLY_ORDER_TYPE_OUTPUT, Enum::APPLY_ORDER_TYPE_APPLY, Enum::APPLY_ORDER_TYPE_RETURN
            ])) {
                if (!$isTagPassiveExist) {
                    throw new Exception("无源标签为'{$applyOrderResource->tag_passive}'的资源不存在");
                }
                $model = ResourceDetail::findOne(['tag_passive' => $applyOrderResource->tag_passive]);
                $model->changeStatusByApplyOrderType($applyOrderType, $resource, $applyOrderResource->applyOrder->pick_type == ApplyOrder::PICK_TYPE_MAINTENANCE);
                $model->save(false);
            } else {
                throw new Exception('未知的 applyOrderType');
            }
            // 修改资源库存
            Resource::updateCurrentStockByApplyOrderType($applyOrderType, $model->resource_id, $model->quantity);
            // 修改货位库存
            Container::updateCurrentQuantityByApplyOrderType($applyOrderType, $model->container_id);
            // 创建操作明细
            ResourceDetailOperation::createOne($applyOrderResource->apply_order_id, $model->id, $model->type, $applyOrderType, $applyOrderResource->remark);
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}

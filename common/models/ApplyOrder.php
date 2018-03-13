<?php

namespace common\models;

use common\models\base\Enum;
use Yii;

/**
 * This is the model class for table "apply_order".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $person_id
 * @property string $reason
 * @property string $delete_reason
 * @property integer $pick_type
 * @property integer $return_at
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Person $person
 * @property ApplyOrderDetail[] $applyOrderDetails
 * @property ApplyOrderResource[] $applyOrderResources
 * @property ApplyOrderResource[] $applyOrderReturnResources
 */
class ApplyOrder extends \common\models\base\ActiveRecord
{
    const SCENARIO_DELETE = 'delete'; // 作废

    const PICK_TYPE_USE = 10;
    const PICK_TYPE_MAINTENANCE = 20; // 保养归还后会更新维护时间
    const PICK_TYPE_SEAL_OFF = 30;

    const STATUS_APPLYING = 0; // 申请中
    const STATUS_OPERATE_PRINT = 1; // 打印，仅作状态校验
    const STATUS_OPERATE_UPDATE = 2; // 修改，仅作状态校验
    const STATUS_OPERATE_RETURN = 3; // 退还
    const STATUS_AUDITED = 10; // 审核通过
    const STATUS_OVER = 30; // 已完成
    const STATUS_RETURN_OVER = 40;// 已归还
    const STATUS_DELETE = 99; // 作废

    public static $pickTypeData = [
        self::PICK_TYPE_USE => '使用',
        self::PICK_TYPE_MAINTENANCE => '保养',
        self::PICK_TYPE_SEAL_OFF => '拆封'
    ];

    public static $statusData = [
        self::STATUS_APPLYING => '申请中',
        self::STATUS_AUDITED => '审核通过',
        self::STATUS_OVER => '已完成',
        self::STATUS_RETURN_OVER => '已归还',
        self::STATUS_DELETE => '作废',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'apply_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'person_id', 'reason'], 'required'],
            [['type', 'person_id', 'pick_type', 'return_at', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['reason', 'delete_reason'], 'string', 'max' => 255],
            [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['person_id' => 'id']],
            [['delete_reason'], 'required', 'on' => static::SCENARIO_DELETE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '类别',
            'person_id' => '申请人 ID',
            'reason' => '理由',
            'delete_reason' => '作废理由',
            'pick_type' => '申领类型',
            'return_at' => '归还时间',
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
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplyOrderDetails()
    {
        return $this->hasMany(ApplyOrderDetail::className(), ['apply_order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplyOrderResources()
    {
        return $this->hasMany(ApplyOrderResource::className(), ['apply_order_id' => 'id'])
            ->onCondition(['is_return' => 0]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplyOrderReturnResources()
    {
        return $this->hasMany(ApplyOrderResource::className(), ['apply_order_id' => 'id'])
            ->onCondition(['is_return' => 1]);
    }

    /**
     * 类别操作：入库、出库、申领、归还
     * @return string
     */
    public function getTypeName()
    {
        return $this->toName($this->type, Enum::$applyOrderTypeData);
    }

    /**
     * 申领类型：使用、保养、拆封
     * @return string
     */
    public function getPickTypeName()
    {
        return $this->toName($this->pick_type, self::$pickTypeData);
    }

    /**
     * 申领类型：使用、保养、拆封
     * @return string
     */
    public function getStatusName()
    {
        return $this->toName($this->status, self::$statusData);
    }

    /**
     * 检查状态
     * @param $toStatus
     * @return bool
     */
    public function checkStatus($toStatus)
    {
        switch ($toStatus) {
            case self::STATUS_APPLYING:
                return true;
            case self::STATUS_OPERATE_PRINT:
            case self::STATUS_OPERATE_UPDATE:
            case self::STATUS_AUDITED:
            case self::STATUS_OPERATE_RETURN;
                return $this->status == self::STATUS_APPLYING;
            case self::STATUS_OVER:
                return $this->status == self::STATUS_AUDITED;
            case self::STATUS_RETURN_OVER:
                return $this->status == self::STATUS_OVER;
            case self::STATUS_DELETE:
                return !in_array($this->status, [self::STATUS_DELETE, self::STATUS_OVER, self::STATUS_RETURN_OVER]);
            default:
                return false;
        }
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function cloneOne()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 复制申请单
            $model = new static();
            $model->attributes = $this->attributes;
            $model->status = static::STATUS_APPLYING;
            unset($model['delete_reason'], $model['return_at']);
            $model->save(false);
            // 复制申请单详情
            $rows = [];
            foreach ($this->applyOrderDetails as $applyOrderDetail) {
                $rows[] = [
                    'apply_order_id' => $model->id,
                    'resource_id' => $applyOrderDetail->resource_id,
                    'quantity' => $applyOrderDetail->quantity,
                ];
            }
            if (count($rows) > 0) {
                Yii::$app->db->createCommand()->batchInsert(
                    ApplyOrderDetail::tableName(),
                    array_keys($rows[0]),
                    $rows
                )->execute();
            }

            $transaction->commit();
            return ['type' => 'success', 'msg' => 'ok'];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['type' => 'error', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 统计最近 N 天的单子数
     * @param int $dayAgo
     * @param null $type
     * @param null $status
     * @return int
     */
    public static function summaryNearCount($dayAgo = 7, $type = null, $status = null)
    {
        $agoTime = intval(time() - $dayAgo * 86400);
        $count = static::find()
            ->where(['>', 'created_at', $agoTime])
            ->andFilterWhere(['type' => $type])
            ->andFilterWhere(['status' => $status])
            ->count();
        return $count ?: 0;
    }

}

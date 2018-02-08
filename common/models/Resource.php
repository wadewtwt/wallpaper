<?php

namespace common\models;

use common\models\base\Enum;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "resource".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $resource_type_id
 * @property string $name
 * @property integer $min_stock
 * @property integer $current_stock
 * @property integer $scrap_cycle
 * @property integer $maintenance_cycle
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property ResourceType $resourceType
 * @property ApplyOrderDetail[] $applyOrderDetails
 * @property ApplyOrderResource[] $applyOrderResources
 * @property ResourceDetail[] $resourceDetails
 */
class Resource extends \common\models\base\ActiveRecord
{
    const TYPE_EXPENDABLE = 10;
    const TYPE_DEVICE = 20;

    const STATUS_NORMAL = 0;
    const STATUS_DELETED = 10;

    public static $typeData = [
        self::TYPE_EXPENDABLE => '消耗品',
        self::TYPE_DEVICE => '设备',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resource';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'resource_type_id', 'name'], 'required'],
            [['type', 'resource_type_id', 'min_stock', 'current_stock', 'scrap_cycle', 'maintenance_cycle', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '类型:消耗品、设备',
            'resource_type_id' => '分类',
            'name' => '名称',
            'min_stock' => '最低库存',
            'current_stock' => '当前库存',
            'scrap_cycle' => '报废周期（天）',
            'maintenance_cycle' => '维护周期（天）',
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
    public function getResourceType()
    {
        return $this->hasOne(ResourceType::className(), ['id' => 'resource_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplyOrderDetails()
    {
        return $this->hasMany(ApplyOrderDetail::className(), ['resource_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplyOrderResources()
    {
        return $this->hasMany(ApplyOrderResource::className(), ['resource_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResourceDetails()
    {
        return $this->hasMany(ResourceDetail::className(), ['resource_id' => 'id']);
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->toName($this->type, self::$typeData);
    }

    /**
     * 是否可以删除
     * @return true|string
     */
    public function canDelete()
    {
        $has = $this->getResourceDetails()
            ->andWhere(['status' => [ResourceDetail::STATUS_NORMAL, ResourceDetail::STATUS_PICKED]])
            ->select(['id'])->limit(1)->one();
        if ($has) {
            return '该资源下还存在明细，请先将资源出库';
        }
        return true;
    }

    /**
     * @param $applyOrderType
     * @param $resourceId
     * @param $quantity
     * @throws Exception
     */
    public static function updateCurrentStockByApplyOrderType($applyOrderType, $resourceId, $quantity)
    {
        if (in_array($applyOrderType, [Enum::APPLY_ORDER_TYPE_OUTPUT, Enum::APPLY_ORDER_TYPE_APPLY])) {
            $quantity = -$quantity;
        } elseif (in_array($applyOrderType, [Enum::APPLY_ORDER_TYPE_INPUT, Enum::APPLY_ORDER_TYPE_RETURN])) {
            $quantity = +$quantity;
        } else {
            throw new Exception('未知的 operation');
        }
        $totalCount = count((array)$resourceId);
        $successCount = static::updateAllCounters(['current_stock' => $quantity], ['id' => $resourceId]);
        if ($successCount != $totalCount) {
            throw new Exception('资源库存更新失败');
        }
    }

    /**
     * @param null $type
     * @param bool $map
     * @param null|array $ids
     * @param bool $withCurrentStock 是否包含当前库存
     * @return array|Resource[]
     */
    public static function findAllIdName($type = null, $map = true, $ids = null, $withCurrentStock = false)
    {
        $select = ['id', 'name'];
        $toArrayProperties = [
            self::className() => ['id', 'name']
        ];
        if ($withCurrentStock) {
            $select = array_merge($select, ['current_stock']);
            $toArrayProperties = [
                self::className() => [
                    'id',
                    'name' => function (self $model) {
                        return $model->name . "({$model->current_stock})";
                    }
                ]
            ];
        }
        $query = self::find()->select($select)->andWhere(['status' => static::STATUS_NORMAL]);
        if ($type !== null) {
            $query->andWhere(['type' => $type]);
        }
        if ($ids !== null) {
            $query->andWhere(['id' => $ids]);
        }
        $models = ArrayHelper::toArray($query->all(), $toArrayProperties);
        if ($map) {
            return ArrayHelper::map($models, 'id', 'name');
        }
        return $models;
    }
}

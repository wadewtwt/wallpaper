<?php

namespace common\models;

use common\models\base\Enum;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "container".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property integer $total_quantity
 * @property integer $current_quantity
 * @property string $remark
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property ApplyOrderResource[] $applyOrderResources
 * @property Store $store
 * @property ResourceDetail[] $resourceDetails
 */
class Container extends \common\models\base\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'container';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'name', 'total_quantity'], 'required'],
            [['store_id', 'total_quantity', 'current_quantity', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name', 'remark'], 'string', 'max' => 255],
            [['store_id'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['store_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => '仓库 ID',
            'name' => '名称',
            'total_quantity' => '货位数量',
            'current_quantity' => '当前数量',
            'remark' => '备注',
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
    public function getApplyOrderResources()
    {
        return $this->hasMany(ApplyOrderResource::className(), ['container_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResourceDetails()
    {
        return $this->hasMany(ResourceDetail::className(), ['container_id' => 'id']);
    }

    /**
     * @return int
     */
    public function getFreeQuantity()
    {
        return $this->total_quantity - $this->current_quantity;
    }

    /**
     * @param bool $map
     * @param bool $withFreeQuantity
     * @return array|Container[]
     */
    public static function findAllIdName($map = false, $withFreeQuantity = false)
    {
        $select = ['id', 'name'];
        $toArrayProperties = [
            self::className() => ['id', 'name']
        ];
        if ($withFreeQuantity) {
            $select = array_merge($select, ['current_quantity', 'total_quantity']);
            $toArrayProperties = [
                self::className() => [
                    'id',
                    'name' => function (self $model) {
                        return $model->name . "({$model->getFreeQuantity()})";
                    }
                ]
            ];
        }
        $models = ArrayHelper::toArray(self::find()->select($select)->all(), $toArrayProperties);
        if ($map) {
            return ArrayHelper::map($models, 'id', 'name');
        }
        return $models;
    }

    /**
     * 根据申请单类型更改当前库存
     * @param $applyOrderType
     * @param $containerId
     * @param int $quantity
     * @throws Exception
     */
    public static function updateCurrentQuantityByApplyOrderType($applyOrderType, $containerId, $quantity = 1)
    {
        if (in_array($applyOrderType, [Enum::APPLY_ORDER_TYPE_INPUT, Enum::APPLY_ORDER_TYPE_RETURN])) {
            $quantity = +$quantity;
        } else if (in_array($applyOrderType, [Enum::APPLY_ORDER_TYPE_OUTPUT, Enum::APPLY_ORDER_TYPE_APPLY])) {
            $quantity = -$quantity;
        } else {
            throw new Exception('未知的 applyOrderType');
        }
        $count = static::updateAllCounters(['current_quantity' => $quantity], ['id' => $containerId]);
        if ($count != 1) {
            throw new Exception('货位库存处理失败');
        }
    }

    /**
     * 计算总货架数
     * @return int|string
     */
    public static function countAllContainer()
    {
        return self::find()->count();
    }
}

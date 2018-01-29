<?php

namespace common\models;

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
 * @property ApplyOrderDetailResource[] $applyOrderDetailResources
 * @property Store $store
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
            [['store_id', 'name', 'total_quantity', 'current_quantity'], 'required'],
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
    public function getApplyOrderDetailResources()
    {
        return $this->hasMany(ApplyOrderDetailResource::className(), ['container_id' => 'id']);
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
     * @param bool $map
     * @param bool $withQuantity
     * @return array|Container[]
     */
    public static function findAllIdName($map = false, $withQuantity = false)
    {
        $select = ['id', 'name'];
        $toArrayProperties = [
            self::className() => ['id', 'name']
        ];
        if ($withQuantity) {
            $select += ['free_quantity', 'total_quantity'];
            $toArrayProperties = [
                self::className() => [
                    'id',
                    'name' => function (self $model) {
                        return $model->name . "({$model->free_quantity}/{$model->total_quantity}";
                    }
                ]
            ];
        }
        $models = ArrayHelper::toArray(self::find()->select($select)->asArray()->all(), $toArrayProperties);
        if ($map) {
            return ArrayHelper::map($models, 'id', 'name');
        }
        return $models;
    }

    /**
     * 计算总货架数
     * @return int|string
     */
    public static function countAllContainer(){
        return self::find()->count();
    }
}

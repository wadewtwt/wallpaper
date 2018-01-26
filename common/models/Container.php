<?php

namespace common\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "container".
 *
 * @property integer $id
 * @property string $name
 * @property integer $total_quantity
 * @property integer $free_quantity
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Device[] $devices
 * @property ExpendableDetail[] $expendableDetails
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
            [['name', 'total_quantity', 'free_quantity'], 'required'],
            [['total_quantity', 'free_quantity', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
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
            'name' => '名称',
            'total_quantity' => '货位数量',
            'free_quantity' => '空闲数量',
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
    public function getDevices()
    {
        return $this->hasMany(Device::className(), ['container_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpendableDetails()
    {
        return $this->hasMany(ExpendableDetail::className(), ['container_id' => 'id']);
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

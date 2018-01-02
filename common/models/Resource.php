<?php

namespace common\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "resource".
 *
 * @property integer $id
 * @property integer $type
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
 * @property ApplyOrderDetail[] $applyOrderDetails
 * @property Device[] $devices
 * @property ExpendableDetail[] $expendableDetails
 */
class Resource extends \common\models\base\ActiveRecord
{
    const TYPE_EXPENDABLE = 10;
    const TYPE_DEVICE = 20;

    const STATUS_NORMAL = 0;
    const STATUS_DELETED = 10;

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
            [['name'],'unique'],
            [['type', 'name'], 'required'],
            [['type', 'min_stock', 'current_stock', 'scrap_cycle', 'maintenance_cycle', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
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
    public function getApplyOrderDetails()
    {
        return $this->hasMany(ApplyOrderDetail::className(), ['resource_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevices()
    {
        return $this->hasMany(Device::className(), ['resource_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpendableDetails()
    {
        return $this->hasMany(ExpendableDetail::className(), ['resource_id' => 'id']);
    }

    public static function findExpendableDevice($isExpendable = true){
        if($isExpendable){
            $type = Resource::TYPE_EXPENDABLE;
        }else{
            $type = Resource::TYPE_DEVICE;
        }
        $model = self::find()->select(['id','name'])->andWhere(['type' => $type])->asArray()->all();
        return ArrayHelper::map($model,'id','name');
    }
}

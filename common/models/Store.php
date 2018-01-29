<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "store".
 *
 * @property integer $id
 * @property string $name
 * @property string $remark
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property AlarmConfig[] $alarmConfigs
 * @property Camera[] $cameras
 * @property Container[] $containers
 * @property Temperature[] $temperatures
 */
class Store extends \common\models\base\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name', 'remark'], 'string', 'max' => 255],
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
    public function getAlarmConfigs()
    {
        return $this->hasMany(AlarmConfig::className(), ['store_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCameras()
    {
        return $this->hasMany(Camera::className(), ['store_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContainers()
    {
        return $this->hasMany(Container::className(), ['store_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemperatures()
    {
        return $this->hasMany(Temperature::className(), ['store_id' => 'id']);
    }
}

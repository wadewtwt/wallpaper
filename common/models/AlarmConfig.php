<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "alarm_config".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $camera_id
 * @property integer $type
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property AlarmCall[] $alarmCalls
 * @property Camera $camera
 * @property Store $store
 * @property AlarmRecord[] $alarmRecords
 */
class AlarmConfig extends \common\models\base\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alarm_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'camera_id', 'type'], 'required'],
            [['store_id', 'camera_id', 'type', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['camera_id'], 'exist', 'skipOnError' => true, 'targetClass' => Camera::className(), 'targetAttribute' => ['camera_id' => 'id']],
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
            'camera_id' => '摄像头 ID',
            'type' => '报警类型',
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
    public function getAlarmCalls()
    {
        return $this->hasMany(AlarmCall::className(), ['alarm_config_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCamera()
    {
        return $this->hasOne(Camera::className(), ['id' => 'camera_id']);
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
    public function getAlarmRecords()
    {
        return $this->hasMany(AlarmRecord::className(), ['alarm_config_id' => 'id']);
    }
}

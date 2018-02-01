<?php

namespace common\models;

use Yii;
use common\models\AlarmConfig;

/**
 * This is the model class for table "alarm_record".
 *
 * @property integer $id
 * @property integer $alarm_config_id
 * @property integer $alarm_at
 * @property string $description
 * @property integer $solve_id
 * @property integer $solve_at
 * @property string $solve_description
 * @property integer $store_id
 * @property integer $camera_id
 * @property integer $type
 * @property integer $status
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Admin $solve
 * @property AlarmConfig $alarmConfig
 */
class AlarmRecord extends \common\models\base\ActiveRecord
{
    const STATUS_OVER = 0;
    const STATUS_PENDING = 10;

    public static $statusData = [
        self::STATUS_OVER => '已完成',
        self::STATUS_PENDING => '待处理'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alarm_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alarm_config_id', 'alarm_at', 'description', 'store_id', 'camera_id', 'type'], 'required'],
            [['alarm_config_id', 'alarm_at', 'solve_id', 'solve_at', 'store_id', 'camera_id', 'type', 'status', 'updated_at', 'updated_by'], 'integer'],
            [['description', 'solve_description'], 'string', 'max' => 255],
            [['solve_id'], 'exist', 'skipOnError' => true, 'targetClass' => Admin::className(), 'targetAttribute' => ['solve_id' => 'id']],
            [['alarm_config_id'], 'exist', 'skipOnError' => true, 'targetClass' => AlarmConfig::className(), 'targetAttribute' => ['alarm_config_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alarm_config_id' => '报警配置 ID',
            'alarm_at' => '报警时间',
            'description' => '报警描述',
            'solve_id' => '处理人',
            'solve_at' => '处理时间',
            'solve_description' => '处理描述',
            'store_id' => '仓库 ID',
            'camera_id' => '摄像头 ID',
            'type' => '报警类型',
            'status' => '状态',
            'updated_at' => '修改时间',
            'updated_by' => '修改人',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolve()
    {
        return $this->hasOne(Admin::className(), ['id' => 'solve_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlarmConfig()
    {
        return $this->hasOne(AlarmConfig::className(), ['id' => 'alarm_config_id']);
    }

    /**
     * @return \yii\db\ActiveQuery|Store
     */
    public function getStore(){
        return $this->hasOne(Store::className(),['id' => 'store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery|Camera
     */
    public function getCamera(){
        return $this->hasOne(Camera::className(),['id' => 'camera_id']);
    }

    /**
     * @return string
     */
    public function getAlarmType(){
        return $this->toname($this->type, AlarmConfig::$typeData);
    }

    public function getAlarmStatus(){
        return $this->toName($this->status, self::$statusData);
    }
}

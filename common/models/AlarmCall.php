<?php

namespace common\models;

/**
 * This is the model class for table "alarm_call".
 *
 * @property integer $id
 * @property integer $alarm_config_id
 * @property integer $camera_id
 * @property integer $store_id
 * @property string $ip
 * @property string $port
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $device_no
 * @property string $remark
 * @property integer $status
 * @property integer $created_at
 *
 * @property AlarmConfig $alarmConfig
 */
class AlarmCall extends \common\models\base\ActiveRecord
{
    const STATUS_NORMAL = 0; // 待处理
    const STATUS_SOLVED = 1; // 已解决

    const ALARM_CONFIG_NULL = 0; // 没有经过联动调起的摄像头

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alarm_call';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alarm_config_id', 'camera_id', 'store_id', 'ip', 'port', 'username', 'password', 'name', 'device_no'], 'required'],
            [['alarm_config_id', 'camera_id', 'store_id', 'status', 'created_at'], 'integer'],
            [['ip', 'username', 'password', 'name', 'device_no', 'remark'], 'string', 'max' => 255],
            [['port'], 'string', 'max' => 10],
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
            'camera_id' => 'Camera ID',
            'store_id' => '仓库 ID',
            'ip' => 'IP',
            'port' => '端口',
            'username' => '用户名',
            'password' => '密码',
            'name' => '名称',
            'device_no' => '设备号',
            'remark' => '备注',
            'status' => '状态',
            'created_at' => '创建时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlarmConfig()
    {
        return $this->hasOne(AlarmConfig::className(), ['id' => 'alarm_config_id']);
    }

    /**
     * @param $alarmRecord AlarmRecord
     * @param null $remark
     */
    public static function createByAlarmRecord($alarmRecord, $remark = null)
    {
        $model = static::findOne(['alarm_config_id' => $alarmRecord->alarm_config_id, 'status' => static::STATUS_NORMAL]);
        if (!$model) {
            $model = new static();
            $camera = $alarmRecord->camera;
            $model->alarm_config_id = $alarmRecord->alarm_config_id;
            $model->fillModelCamera($camera);
            $model->remark = $remark;
            $model->save(false);
        }
    }

    /**
     * @param $camera Camera
     * @param null $remark
     */
    public static function createByCameraView($camera, $remark = null)
    {
        $model = static::findOne([
            'alarm_config_id' => static::ALARM_CONFIG_NULL,
            'camera_id' => $camera->id,
            'status' => static::STATUS_NORMAL
        ]);
        if (!$model) {
            $model = new static();
            $model->alarm_config_id = static::ALARM_CONFIG_NULL;
            $model->fillModelCamera($camera);
            $model->remark = $remark;
            $model->save(false);
        }
    }

    /**
     * @param $camera Camera
     */
    protected function fillModelCamera($camera)
    {
        $this->camera_id = $camera->id;
        $this->store_id = $camera->store_id;
        $this->ip = $camera->ip;
        $this->port = $camera->port;
        $this->username = $camera->username;
        $this->password = $camera->password;
        $this->name = $camera->name;
        $this->device_no = $camera->device_no;
    }
}

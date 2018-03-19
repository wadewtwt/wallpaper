<?php

namespace backend\models\form;

use common\models\AlarmConfig;
use common\models\AlarmRecord;
use yii\base\Model;

class ThirdPartAlarmRecordCreateForm extends Model
{
    public $alarm_config_id;
    public $description;

    public function rules()
    {
        return [
            [['alarm_config_id', 'description'], 'required'],
            [['alarm_config_id'], 'integer'],
            [['description'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'alarm_config_id' => '联动设置ID',
            'description' => '报警描述',
        ];
    }

    public function create()
    {
        $alarmConfig = AlarmConfig::findOne($this->alarm_config_id);
        if (!$alarmConfig) {
            return ['type' => 'error', 'msg' => 'ID为' . $this->alarm_config_id . '的联动设置不存在'];
        }
        if ($alarmConfig->status == AlarmConfig::STATUS_NORMAL) {
            AlarmRecord::createOne($alarmConfig, AlarmRecord::DES_TEMP_OTHER, [
                'description' => $this->description,
            ]);
            return ['type' => 'success', 'msg' => 'ok'];
        }
        return ['type' => 'success', 'msg' => '联动设置未启用或已删除'];
    }
}
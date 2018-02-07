<?php

namespace common\models;

use Yii;
use yii\base\Exception;

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
 * @property Camera $camera
 * @property Store $store
 */
class AlarmRecord extends \common\models\base\ActiveRecord
{
    const SCENARIO_SOLVE = 'solve';

    const STATUS_NORMAL = 0;
    const STATUS_SOLVED = 10;

    public static $statusData = [
        self::STATUS_NORMAL => '待处理',
        self::STATUS_SOLVED => '已解决'
    ];

    // 报警描述
    const DES_TEMP_TEMPERATURE = '温湿度设备【{temperatureName}】当前值【{current}】，超过阀值{downLimit}~{upLimit}';
    const DES_TEMP_ILLEGAL_OUTPUT = '资源【{resourceName}】非法出入库，无源标签号【tagPassive】';

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
            [['alarm_config_id', 'alarm_at', 'store_id', 'camera_id', 'type'], 'required'],
            [['alarm_config_id', 'alarm_at', 'solve_id', 'solve_at', 'store_id', 'camera_id', 'type', 'status', 'updated_at', 'updated_by'], 'integer'],
            [['description', 'solve_description'], 'string', 'max' => 255],
            [['solve_id'], 'exist', 'skipOnError' => true, 'targetClass' => Admin::className(), 'targetAttribute' => ['solve_id' => 'id']],
            [['alarm_config_id'], 'exist', 'skipOnError' => true, 'targetClass' => AlarmConfig::className(), 'targetAttribute' => ['alarm_config_id' => 'id']],
            [['solve_id', 'solve_description'], 'required', 'on' => static::SCENARIO_SOLVE],
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
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery|Camera
     */
    public function getCamera()
    {
        return $this->hasOne(Camera::className(), ['id' => 'camera_id']);
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->toName($this->type, AlarmConfig::$typeData);
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        return $this->toName($this->status, self::$statusData);
    }

    /**
     * 获取描述的 html 显示
     * @return string
     */
    public function getDescriptionHtmlFormat()
    {
        return strtr($this->description, ['【' => '<code>', '】' => '</code>']);
    }

    /**
     * @param $alarmConfig AlarmConfig
     * @param string $desTemplate ALARM_DESCRIPTION 模版
     * @param array $desTemplateParams ALARM_DESCRIPTION 模版的参数
     * @param bool $checkExist 是否检查是否已经存在记录，若检查则存在不重复记录
     * @throws \yii\db\Exception
     */
    public static function createOne($alarmConfig, $desTemplate, $desTemplateParams, $checkExist = true)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $des = static::fillDescriptionWithTemplate($desTemplate, $desTemplateParams);
            if ($checkExist) {
                $model = static::findOne(['alarm_config_id' => $alarmConfig->id, 'status' => static::STATUS_NORMAL]);
            } else {
                $model = null;
            }
            if (!$model) {
                $model = new static();
                $model->alarm_config_id = $alarmConfig->id;
                $model->alarm_at = time();
                $model->description = $des;
                $model->store_id = $alarmConfig->store_id;
                $model->camera_id = $alarmConfig->camera_id;
                $model->type = $alarmConfig->type;
                $model->save(false);
            }
            AlarmCall::createOne($model, $des);

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error('创建报警记录异常');
            Yii::error($e);
        }
    }

    /**
     * 按照模版填充描述
     * @param $template
     * @param $params
     * @return string
     */
    protected static function fillDescriptionWithTemplate($template, $params)
    {
        $replaces = [];
        foreach ((array)$params as $name => $value) {
            $replaces['{' . $name . '}'] = $value;
        }

        $content = ($replaces === []) ? $template : strtr($template, $replaces);
        return $content;
    }

}

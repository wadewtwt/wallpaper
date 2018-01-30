<?php

namespace common\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "camera".
 *
 * @property integer $id
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
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property AlarmConfig[] $alarmConfigs
 * @property Store $store
 */
class Camera extends \common\models\base\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'camera';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'ip', 'port', 'username', 'password', 'name', 'device_no'], 'required'],
            [['store_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['ip', 'username', 'password', 'name', 'device_no', 'remark'], 'string', 'max' => 255],
            [['port'], 'string', 'max' => 10],
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
            'ip' => 'IP',
            'port' => '端口',
            'username' => '用户名',
            'password' => '密码',
            'name' => '名称',
            'device_no' => '设备号',
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
        return $this->hasMany(AlarmConfig::className(), ['camera_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

    /**
     * @param bool $map
     * @return array|\yii\db\ActiveRecord[]|Camera
     */
    public static function findAllIdName($map = false)
    {
        $models = static::find()->select(['id', 'name'])->asArray()->all();
        if ($map) {
            return ArrayHelper::map($models, 'id', 'name');
        }
        return $models;
    }
}

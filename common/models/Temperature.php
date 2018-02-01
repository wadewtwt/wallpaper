<?php

namespace common\models;

use Yii;
use common\models\Store;

/**
 * This is the model class for table "temperature".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $ip
 * @property string $port
 * @property string $device_no
 * @property string $down_limit
 * @property string $up_limit
 * @property string $current
 * @property integer $current_updated_at
 * @property string $remark
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Store $store
 */
class Temperature extends \common\models\base\ActiveRecord
{
    const STATUS_NORMAL = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'temperature';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'ip', 'port', 'device_no', 'down_limit', 'up_limit'], 'required'],
            [['store_id', 'current_updated_at', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['down_limit', 'up_limit', 'current'], 'number'],
            [['ip', 'device_no', 'remark'], 'string', 'max' => 255],
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
            'device_no' => '设备号',
            'down_limit' => '下阀值',
            'up_limit' => '上阀值',
            'current' => '当前值',
            'current_updated_at' => '当前值更新时间',
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
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

}

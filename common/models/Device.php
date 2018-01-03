<?php

namespace common\models;

/**
 * This is the model class for table "device".
 *
 * @property integer $id
 * @property integer $resource_id
 * @property integer $container_id
 * @property string $rfid
 * @property integer $is_online
 * @property integer $online_change_at
 * @property integer $maintenance_at
 * @property integer $scrap_at
 * @property integer $quantity
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Container $container
 * @property Resource $resource
 * @property DeviceDetail[] $deviceDetails
 */
class Device extends \common\models\base\ActiveRecord
{
    const DATA_ONLINE = 0;
    const DATA_OFFLINE = 10;
    public static $DataIsOnline = [
        self::DATA_ONLINE => '在线',
        self::DATA_OFFLINE => '离线'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resource_id', 'container_id', 'rfid', 'online_change_at', 'maintenance_at', 'scrap_at', 'quantity'], 'required'],
            [['resource_id', 'container_id', 'is_online', 'online_change_at', 'maintenance_at', 'scrap_at', 'quantity', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['rfid'], 'string', 'max' => 255],
            [['container_id'], 'exist', 'skipOnError' => true, 'targetClass' => Container::className(), 'targetAttribute' => ['container_id' => 'id']],
            [['resource_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resource::className(), 'targetAttribute' => ['resource_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resource_id' => '资源 ID',
            'container_id' => '货位 ID',
            'rfid' => 'RFID',
            'is_online' => '是否在线',
            'online_change_at' => '在线离线时间',
            'maintenance_at' => '最近维护时间',
            'scrap_at' => '报废时间',
            'quantity' => '数量',
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
    public function getContainer()
    {
        return $this->hasOne(Container::className(), ['id' => 'container_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResource()
    {
        return $this->hasOne(Resource::className(), ['id' => 'resource_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceDetails()
    {
        return $this->hasMany(DeviceDetail::className(), ['device_id' => 'id']);
    }

    public function getDataIsOnline(){
        return $this->toName($this->is_online,self::$DataIsOnline);
    }

}

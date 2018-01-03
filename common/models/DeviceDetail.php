<?php

namespace common\models;

/**
 * This is the model class for table "device_detail".
 *
 * @property integer $id
 * @property integer $device_id
 * @property integer $operation
 * @property string $remark
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Device $device
 */
class DeviceDetail extends \common\models\base\ActiveRecord
{
    const STOCK_INPUT = 10;
    const STOCK_OUTPUT = 20;
    public static $stockOperation = [
        self::STOCK_INPUT => '入库' ,
        self::STOCK_OUTPUT => '出库'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_id', 'operation', 'remark'], 'required'],
            [['device_id', 'operation', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['remark'], 'string', 'max' => 255],
            [['device_id'], 'exist', 'skipOnError' => true, 'targetClass' => Device::className(), 'targetAttribute' => ['device_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_id' => '设备 ID',
            'operation' => '操作',
            'remark' => '说明',
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
    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['id' => 'device_id']);
    }

    public function getDeviceDetailOperation(){
        return $this->toName($this->operation, self::$stockOperation);
    }
}

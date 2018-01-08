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
 *
 * @property Device $device
 */
class DeviceDetail extends \common\models\base\ActiveRecord
{
    const OPERATION_INPUT = 10;
    const OPERATION_OUTPUT = 20;
    const OPERATION_APPLY = 30;
    const OPERATION_RETURN = 40;

    public static $operationData = [
        self::OPERATION_INPUT => '入库',
        self::OPERATION_OUTPUT => '出库',
        self::OPERATION_APPLY => '申领',
        self::OPERATION_RETURN => '归还',
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
            [['device_id', 'operation'], 'required'],
            [['device_id', 'operation', 'status', 'created_at', 'created_by'], 'integer'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['id' => 'device_id']);
    }

    /**
     * @return string
     */
    public function getOperationName()
    {
        return $this->toName($this->operation, self::$operationData);
    }

    /**
     * @param $deviceId
     * @param $operation
     * @param $remark
     */
    public static function createOne($deviceId, $operation, $remark = null)
    {
        $model = new DeviceDetail();
        $model->device_id = $deviceId;
        $model->operation = $operation;
        $model->remark = $remark;
        $model->save(false);
    }

    public function getResource(){
        return $this->hasOne(Resource::className(),['id' => 'resource_id'])
            ->viaTable('device',['id' => 'device_id']);
    }

}

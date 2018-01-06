<?php

namespace common\models;

use Yii;
use yii\base\Exception;

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
 * @property \common\models\Resource $resource
 * @property DeviceDetail[] $deviceDetails
 */
class Device extends \common\models\base\ActiveRecord
{
    const STATUS_NORMAL = 0; // 正常在库
    const STATUS_OUTPUT = 10; // 已出库
    const STATUS_PICKED = 20; // 被领走

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

    /**
     * 创建一次设备操作
     * @param $resource \common\models\Resource
     * @param $rfid
     * @param $quantity
     * @param $containerId
     * @param $deviceDetailOperation
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public static function createOne($resource, $rfid, $quantity, $containerId, $deviceDetailOperation)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 修改设备信息
            if ($deviceDetailOperation == DeviceDetail::OPERATION_INPUT) {
                $device = Device::findOne(['rfid' => $rfid, 'status' => self::STATUS_NORMAL]);
                if ($device) {
                    throw new Exception("RFID 为'{$rfid}'的设备已存在");
                }
                $device = new Device();
                $device->resource_id = $resource->id;
                $device->container_id = $containerId;
                $device->rfid = $rfid;
                $device->is_online = 0;
                $device->online_change_at = time();
                $device->maintenance_at = time() + ($resource->maintenance_cycle * 86400);
                $device->scrap_at = time() + ($resource->scrap_cycle * 86400);
                $device->quantity = $quantity;
                $device->status = self::STATUS_NORMAL;
                $device->save(false);
            } elseif (in_array($deviceDetailOperation, [DeviceDetail::OPERATION_OUTPUT, DeviceDetail::OPERATION_APPLY, DeviceDetail::OPERATION_RETURN])) {
                $device = Device::findOne([
                    'rfid' => $rfid, 'quantity' => $quantity,
                    'resource_id' => $resource->id, 'container_id' => $containerId,
                    'status' => self::STATUS_NORMAL,
                ]);
                if (!$device) {
                    $containerName = Container::find()->select(['name'])->where(['id' => $containerId])->scalar();
                    throw new Exception("货位'{$containerName}'上 RFID 为'{$rfid}'，且数量为'{$quantity}'的'{$resource->name}'不存在或已出库");
                }
                if ($deviceDetailOperation == DeviceDetail::OPERATION_OUTPUT) {
                    $device->status = self::STATUS_OUTPUT;
                } elseif ($deviceDetailOperation == DeviceDetail::OPERATION_APPLY) {
                    $device->status = self::STATUS_PICKED;
                } elseif ($deviceDetailOperation == DeviceDetail::OPERATION_RETURN) {
                    $device->status = self::STATUS_NORMAL;
                } else {
                    throw new Exception('未知的 $deviceDetailOperation');
                }
                $device->save(false);
            } else {
                throw new Exception('未知的 $deviceDetailOperation');
            }
            // 修改资源库存
            $resource = $device->resource;
            if (in_array($deviceDetailOperation, [DeviceDetail::OPERATION_OUTPUT, DeviceDetail::OPERATION_APPLY])) {
                $resource->current_stock -= $device->quantity;
            } elseif (in_array($deviceDetailOperation, [DeviceDetail::OPERATION_INPUT, DeviceDetail::OPERATION_RETURN])) {
                $resource->current_stock += $device->quantity;
            } else {
                throw new Exception('未知的 operation');
            }
            $resource->save(false);
            // 更新明细
            DeviceDetail::createOne($device->id, $deviceDetailOperation);
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

}

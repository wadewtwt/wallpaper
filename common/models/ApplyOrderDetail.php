<?php

namespace common\models;

use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "apply_order_detail".
 *
 * @property integer $id
 * @property integer $apply_order_id
 * @property integer $resource_id
 * @property integer $container_id
 * @property string $rfid
 * @property integer $quantity
 *
 * @property Container $container
 * @property \common\models\Resource $resource
 * @property ApplyOrder $applyOrder
 */
class ApplyOrderDetail extends \common\models\base\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'apply_order_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['apply_order_id', 'resource_id', 'container_id'], 'required'],
            [['apply_order_id', 'resource_id', 'container_id', 'quantity'], 'integer'],
            [['rfid'], 'string', 'max' => 255],
            [['container_id'], 'exist', 'skipOnError' => true, 'targetClass' => Container::className(), 'targetAttribute' => ['container_id' => 'id']],
            [['apply_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplyOrder::className(), 'targetAttribute' => ['apply_order_id' => 'id']],
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
            'apply_order_id' => '入库单 ID',
            'resource_id' => '资源 ID',
            'container_id' => '货位 ID',
            'rfid' => 'RFID',
            'quantity' => '数量',
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
    public function getApplyOrder()
    {
        return $this->hasOne(ApplyOrder::className(), ['id' => 'apply_order_id']);
    }

    /**
     * 处理明细操作
     * @param $applyOrderType
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function solveOrderDetail($applyOrderType)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            switch ($applyOrderType) {
                case ApplyOrder::TYPE_INPUT:
                    $containerFreeCountPlus = -1; // 货位空闲数增加数
                    $expendableOperation = ExpendableDetail::OPERATION_INPUT; // 消耗品操作
                    $deviceOperation = DeviceDetail::OPERATION_INPUT; // 设备明细操作
                    break;
                case ApplyOrder::TYPE_OUTPUT:
                    $containerFreeCountPlus = 1;
                    $expendableOperation = ExpendableDetail::OPERATION_OUTPUT;
                    $deviceOperation = DeviceDetail::OPERATION_OUTPUT;
                    break;
                case ApplyOrder::TYPE_APPLY:
                    $containerFreeCountPlus = 1;
                    $expendableOperation = ExpendableDetail::OPERATION_APPLY;
                    $deviceOperation = DeviceDetail::OPERATION_APPLY;
                    break;
                case ApplyOrder::TYPE_RETURN:
                    $containerFreeCountPlus = -1;
                    $expendableOperation = ExpendableDetail::OPERATION_RETURN;
                    $deviceOperation = DeviceDetail::OPERATION_RETURN;
                    break;
                default:
                    throw new Exception('未知的 $applyOrderType');
            }

            // 修改对应资源的信息
            $resource = $this->resource;
            if ($resource->type == Resource::TYPE_EXPENDABLE) {
                ExpendableDetail::createOne($resource, $this->rfid, $this->quantity, $this->container_id, $expendableOperation);
            } elseif ($resource->type == Resource::TYPE_DEVICE) {
                Device::createOne($resource, $this->rfid, $this->quantity, $this->container_id, $deviceOperation);
            } else {
                throw new Exception('未知的 type');
            }
            // 处理货位信息
            $count = Container::updateAllCounters(['free_quantity' => $containerFreeCountPlus], ['id' => $this->container_id]);
            if ($count != 1) {
                throw new Exception('货位库存处理失败');
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

}

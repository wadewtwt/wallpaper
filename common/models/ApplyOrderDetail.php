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

    public function saveToResource()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 资源保存到对应表中
            $resource = $this->resource;
            if ($resource->type == Resource::TYPE_EXPENDABLE) {
                ExpendableDetail::createOne($resource, $this->rfid, $this->quantity, $this->container_id, ExpendableDetail::OPERATION_INPUT);
            } elseif ($resource->type == Resource::TYPE_DEVICE) {
                Device::createOne($resource, $this->rfid, $this->quantity, $this->container_id);
            } else {
                throw new Exception('未知的 type');
            }
            // 货位库存减少
            $count = Container::updateAllCounters(['free_quantity' => -1], ['id' => $this->container_id]);
            if ($count != 1) {
                throw new Exception('货位库存减少失败');
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}

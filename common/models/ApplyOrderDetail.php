<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "apply_order_detail".
 *
 * @property integer $id
 * @property integer $apply_order_id
 * @property integer $resource_id
 * @property integer $container_id
 * @property string $rfid
 * @property integer $quantity
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
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
            [['apply_order_id', 'resource_id', 'container_id', 'quantity', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
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
    public function getApplyOrder()
    {
        return $this->hasOne(ApplyOrder::className(), ['id' => 'apply_order_id']);
    }
}

<?php

namespace common\models;

/**
 * This is the model class for table "apply_order_detail".
 *
 * @property integer $id
 * @property integer $apply_order_id
 * @property integer $resource_id
 * @property integer $quantity
 *
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
            [['apply_order_id', 'resource_id'], 'required'],
            [['apply_order_id', 'resource_id', 'quantity'], 'integer'],
            [['resource_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resource::className(), 'targetAttribute' => ['resource_id' => 'id']],
            [['apply_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplyOrder::className(), 'targetAttribute' => ['apply_order_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'apply_order_id' => '申请单 ID',
            'resource_id' => '资源 ID',
            'quantity' => '数量',
        ];
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

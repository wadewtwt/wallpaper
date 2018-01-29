<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "apply_order_detail_resource".
 *
 * @property integer $id
 * @property integer $apply_order_detail_id
 * @property integer $resource_id
 * @property integer $container_id
 * @property string $tag_passive
 * @property integer $quantity
 *
 * @property Container $container
 * @property ApplyOrderDetail $applyOrderDetail
 * @property Resource $resource
 */
class ApplyOrderDetailResource extends \common\models\base\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'apply_order_detail_resource';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['apply_order_detail_id', 'resource_id', 'container_id'], 'required'],
            [['apply_order_detail_id', 'resource_id', 'container_id', 'quantity'], 'integer'],
            [['tag_passive'], 'string', 'max' => 255],
            [['container_id'], 'exist', 'skipOnError' => true, 'targetClass' => Container::className(), 'targetAttribute' => ['container_id' => 'id']],
            [['apply_order_detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplyOrderDetail::className(), 'targetAttribute' => ['apply_order_detail_id' => 'id']],
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
            'apply_order_detail_id' => '申请单明细 ID',
            'resource_id' => '资源 ID',
            'container_id' => '货位 ID',
            'tag_passive' => '无源标签',
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
    public function getApplyOrderDetail()
    {
        return $this->hasOne(ApplyOrderDetail::className(), ['id' => 'apply_order_detail_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResource()
    {
        return $this->hasOne(Resource::className(), ['id' => 'resource_id']);
    }
}

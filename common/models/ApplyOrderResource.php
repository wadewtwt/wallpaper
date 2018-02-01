<?php

namespace common\models;

/**
 * This is the model class for table "apply_order_resource".
 *
 * @property integer $id
 * @property integer $apply_order_id
 * @property integer $resource_id
 * @property integer $container_id
 * @property string $tag_active
 * @property string $tag_passive
 * @property integer $quantity
 * @property string $remark
 *
 * @property Container $container
 * @property ApplyOrder $applyOrder
 * @property \common\models\Resource $resource
 */
class ApplyOrderResource extends \common\models\base\ActiveRecord
{
    const SCENARIO_INPUT = 'input'; // 入库

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'apply_order_resource';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['apply_order_id', 'resource_id', 'container_id'], 'required'],
            [['apply_order_id', 'resource_id', 'container_id', 'quantity'], 'integer'],
            [['tag_active', 'tag_passive', 'remark'], 'string', 'max' => 255],
            [['apply_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplyOrder::className(), 'targetAttribute' => ['apply_order_id' => 'id']],
            [['container_id'], 'exist', 'skipOnError' => true, 'targetClass' => Container::className(), 'targetAttribute' => ['container_id' => 'id']],
            [['resource_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resource::className(), 'targetAttribute' => ['resource_id' => 'id']],
            [['tag_active', 'tag_passive'], 'required', 'on' => static::SCENARIO_INPUT],
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
            'container_id' => '货位 ID',
            'tag_active' => '有源标签',
            'tag_passive' => '无源标签',
            'quantity' => '数量',
            'remark' => '备注',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplyOrder()
    {
        return $this->hasOne(ApplyOrder::className(), ['id' => 'apply_order_id']);
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

    public function solveResourceDetail($applyOrderType)
    {

    }
}

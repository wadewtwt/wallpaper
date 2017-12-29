<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "expendable_detail".
 *
 * @property integer $id
 * @property integer $resource_id
 * @property integer $container_id
 * @property string $rfid
 * @property integer $operation
 * @property integer $quantity
 * @property string $remark
 * @property integer $scrap_at
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 *
 * @property Container $container
 * @property Resource $resource
 */
class ExpendableDetail extends \common\models\base\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expendable_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resource_id', 'container_id', 'rfid', 'operation', 'quantity', 'remark', 'scrap_at'], 'required'],
            [['resource_id', 'container_id', 'operation', 'quantity', 'scrap_at', 'status', 'created_at', 'created_by'], 'integer'],
            [['rfid', 'remark'], 'string', 'max' => 255],
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
            'operation' => '操作:出库、入库',
            'quantity' => '数量',
            'remark' => '说明',
            'scrap_at' => '报废时间',
            'status' => '状态',
            'created_at' => '创建时间',
            'created_by' => '创建人',
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
}

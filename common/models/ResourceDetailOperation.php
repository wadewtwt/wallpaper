<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "resource_detail_operation".
 *
 * @property integer $id
 * @property integer $resource_detail_id
 * @property integer $operation
 * @property string $remark
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 *
 * @property ResourceDetail $resourceDetail
 */
class ResourceDetailOperation extends \common\models\base\ActiveRecord
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
        return 'resource_detail_operation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resource_detail_id', 'operation'], 'required'],
            [['resource_detail_id', 'operation', 'status', 'created_at', 'created_by'], 'integer'],
            [['remark'], 'string', 'max' => 255],
            [['resource_detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResourceDetail::className(), 'targetAttribute' => ['resource_detail_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resource_detail_id' => '设备 ID',
            'operation' => '操作',
            'remark' => '备注',
            'status' => '状态',
            'created_at' => '创建时间',
            'created_by' => '创建人',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResourceDetail()
    {
        return $this->hasOne(ResourceDetail::className(), ['id' => 'resource_detail_id']);
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
        $model = new static();
        $model->resource_detail_id = $deviceId;
        $model->operation = $operation;
        $model->remark = $remark;
        $model->save(false);
    }
}

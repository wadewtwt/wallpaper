<?php

namespace common\models;

use Yii;
use yii\base\Exception;

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
    const OPERATION_INPUT = 10;
    const OPERATION_OUTPUT = 20;
    const OPERATION_APPLY = 30;
    const OPERATION_RETURN = 40;

    public static $operationData = [
        self::OPERATION_INPUT => '入库',
        self::OPERATION_OUTPUT => '出库',
        self::OPERATION_APPLY => '申领',
        self::OPERATION_RETURN => '退还'
    ];

    const STATUS_NORMAL = 0;
    const STATUS_DELETED = 10;

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
            [['resource_id', 'container_id', 'rfid', 'operation', 'quantity', 'scrap_at'], 'required'],
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
            'operation' => '操作',
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

    /**
     * @return string
     */
    public function getOperationName()
    {
        return $this->toName($this->operation, self::$operationData);
    }

    /**
     * @param \common\models\Resource $resource
     * @param $rfid
     * @param $quantity
     * @param $containerId
     * @param $operation
     * @param $remark
     * @throws \yii\db\Exception
     */
    public static function createOne(\common\models\Resource $resource, $rfid, $quantity, $containerId, $operation, $remark = null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 修改货位库存
            if (in_array($operation, [self::OPERATION_INPUT, self::OPERATION_RETURN])) {
                $resource->current_stock += $quantity;
            } elseif (in_array($operation, [self::OPERATION_OUTPUT, self::OPERATION_APPLY])) {
                $resource->current_stock -= $quantity;
            } else {
                throw new Exception('未知的 operation');
            }
            $resource->save(false);
            // 创建消耗品明细
            $model = new self();
            $model->resource_id = $resource->id;
            $model->rfid = $rfid;
            $model->quantity = $quantity;
            $model->container_id = $containerId;
            $model->operation = $operation;
            $model->remark = $remark;
            $model->scrap_at = time() + ($resource->scrap_cycle * 86400);
            $model->save(false);

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

}

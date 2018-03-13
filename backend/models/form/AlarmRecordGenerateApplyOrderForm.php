<?php

namespace backend\models\form;

use common\models\AlarmConfig;
use common\models\AlarmRecord;
use common\models\ApplyOrder;
use common\models\ApplyOrderDetail;
use common\models\ApplyOrderResource;
use common\models\base\Enum;
use common\models\ResourceDetail;
use Yii;
use yii\base\Exception;
use yii\base\Model;

class AlarmRecordGenerateApplyOrderForm extends Model
{
    /**
     * 报警记录id，逗号分隔
     * @var string
     */
    public $alarm_record_ids;
    /**
     * @var integer
     */
    public $apply_order_person_id;
    /**
     * @var string
     */
    public $apply_order_reason;
    /**
     * @var integer
     */
    public $apply_order_pick_type;
    /**
     * @var integer
     */
    public $solve_id;
    /**
     * @var string
     */
    public $solve_description;

    /**
     * @var ApplyOrderDetail[]
     */
    public $applyOrderDetails = [];
    /**
     * @var ApplyOrderResource[]
     */
    public $applyOrderResources = [];

    public function rules()
    {
        return [
            [['apply_order_person_id', 'apply_order_reason', 'apply_order_pick_type', 'solve_id'], 'required'],
            [['alarm_record_ids', 'apply_order_reason', 'solve_description'], 'string'],
            [['apply_order_person_id', 'apply_order_pick_type', 'solve_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'apply_order_person_id' => '调度申请人',
            'apply_order_reason' => '理由',
            'apply_order_pick_type' => '申领类型',
            'solve_id' => '处理人',
            'solve_description' => '处理描述',
        ];
    }

    /**
     * 初始化必要数据
     * @return bool
     * @throws Exception
     */
    public function initData()
    {
        if (!$this->alarm_record_ids) {
            throw new Exception('必须配置 alarm_record_ids');
        }
        $alarmRecordIds = array_filter(explode(',', $this->alarm_record_ids));
        // 所有非法出库的标签
        $tags = array_filter(AlarmRecord::find()->alias('a')->joinWith('alarmConfig b')->select(['a.resource_tag'])->where([
            'a.id' => $alarmRecordIds,
            'a.status' => AlarmRecord::STATUS_NORMAL,
            'b.type' => AlarmConfig::TYPE_ILLEGAL_OUTPUT,
        ])->column());
        $uniqueTags = array_unique($tags);
        if (count($uniqueTags) <= 0) {
            $this->addError('alarm_record_ids', '在当前选择的报警记录中未发现任何在库的资源标签，可能是报警记录已解决，或者该报警记录不是非法出库的类别');
            return false;
        }
        // 所有非法出库，且当前在库的资源
        $resourceDetails = ResourceDetail::find()
            ->andWhere(['tag_active' => $uniqueTags])
            ->orWhere(['tag_passive' => $uniqueTags])
            ->andWhere(['status' => ResourceDetail::STATUS_NORMAL])
            ->all();
        // 设置申请单汇总数据和资源数据
        foreach ($resourceDetails as $resourceDetail) {
            if (isset($this->applyOrderDetails[$resourceDetail->resource_id])) {
                $this->applyOrderDetails[$resourceDetail->resource_id]['quantity'] += $resourceDetail->quantity;
                $this->applyOrderDetails[$resourceDetail->resource_id]['quantity_real'] += $resourceDetail->quantity;
            } else {
                $applyOrderDetail = new ApplyOrderDetail([
                    'resource_id' => $resourceDetail->resource_id,
                    'quantity' => $resourceDetail->quantity,
                    'quantity_real' => $resourceDetail->quantity,
                ]);
                $this->applyOrderDetails[$resourceDetail->resource_id] = $applyOrderDetail;
            }

            $applyOrderResource = new ApplyOrderResource([
                'resource_id' => $resourceDetail->resource_id,
                'container_id' => $resourceDetail->container_id,
                'tag_active' => $resourceDetail->tag_active,
                'tag_passive' => $resourceDetail->tag_passive,
                'quantity' => $resourceDetail->quantity,
            ]);
            $this->applyOrderResources[] = $applyOrderResource;
        }

        return true;
    }

    /**
     * 生成订单和处理
     * @param $operateUserId
     * @return array
     */
    public function generateAndSolve($operateUserId)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 申请单
            $applyOrder = new ApplyOrder([
                'type' => Enum::APPLY_ORDER_TYPE_APPLY,
                'person_id' => $this->apply_order_person_id,
                'reason' => $this->apply_order_reason,
                'pick_type' => $this->apply_order_pick_type,
                'status' => ApplyOrder::STATUS_OVER,
            ]);
            $applyOrder->save(false);
            // 申请单汇总
            foreach ($this->applyOrderDetails as $applyOrderDetail) {
                $applyOrderDetail->apply_order_id = $applyOrder->id;
                $applyOrderDetail->save(false);
            }
            // 申请单资源
            foreach ($this->applyOrderResources as $applyOrderResource) {
                $applyOrderResource->apply_order_id = $applyOrder->id;
                $applyOrderResource->save(false);
                ResourceDetail::operateByApplyOrderType(Enum::APPLY_ORDER_TYPE_APPLY, $applyOrderResource);
            }
            // 批量处理报警
            $batchSolveForm = new AlarmRecordBatchSolveForm([
                'keys' => $this->alarm_record_ids,
                'solve_id' => $this->solve_id,
                'solve_description' => $this->solve_description,
            ]);
            $batchSolveForm->solve($operateUserId);

            $transaction->commit();
            return ['type' => 'success', 'msg' => '报警记录生成申领单成功'];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['type' => 'error', 'msg' => $e->getMessage()];
        }
    }

}
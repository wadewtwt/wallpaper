<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\components\MessageAlert;
use backend\exceptions\StatusNotAllowedException;
use backend\models\ApplyOrderSearch;
use common\components\Tools;
use common\models\ApplyOrder;
use common\models\ApplyOrderDetail;
use common\models\ApplyOrderResource;
use common\models\base\Enum;
use common\models\ResourceDetail;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class ApplyOrderController extends AuthWebController
{
    public $applyOrderType;

    public function init()
    {
        parent::init();
        $allows = [Enum::APPLY_ORDER_TYPE_INPUT, Enum::APPLY_ORDER_TYPE_OUTPUT, Enum::APPLY_ORDER_TYPE_APPLY, Enum::APPLY_ORDER_TYPE_RETURN];
        if (!in_array($this->applyOrderType, $allows)) {
            throw new Exception('applyOrderType 必须配置，且为 ' . implode(',', $allows) . '中的一个');
        }
    }

    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new ApplyOrderSearch([
            'type' => $this->applyOrderType,
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@view/apply-order/index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    // 新增
    public function actionCreate()
    {
        $applyOrder = new ApplyOrder([
            'type' => $this->applyOrderType
        ]);
        $applyOrderDetails = [new ApplyOrderDetail()];

        return $this->handlerCreateAndUpdate($applyOrder, $applyOrderDetails);
    }

    // 修改
    public function actionUpdate($id)
    {
        $applyOrder = $this->findModel($id);
        $applyOrderDetails = $applyOrder->applyOrderDetails;

        if (!$applyOrder->checkStatus(ApplyOrder::STATUS_OPERATE_UPDATE)) {
            throw new StatusNotAllowedException();
        }

        return $this->handlerCreateAndUpdate($applyOrder, $applyOrderDetails);
    }

    // 明细
    public function actionDetail($id)
    {
        $model = $this->findModel($id);

        return $this->render('@view/apply-order/detail', [
            'model' => $model,
        ]);
    }

    // 打印
    public function actionPrint($id)
    {
        $this->layout = 'empty';
        $model = $this->findModel($id);

        if (!$model->checkStatus(ApplyOrder::STATUS_OPERATE_PRINT)) {
            throw new StatusNotAllowedException();
        }

        return $this->render('@view/apply-order/print', [
            'model' => $model,
        ]);
    }

    // 审核通过
    public function actionPass($id)
    {
        $model = $this->findModel($id);

        if (!$model->checkStatus(ApplyOrder::STATUS_AUDITED)) {
            throw new StatusNotAllowedException();
        }

        $model->status = ApplyOrder::STATUS_AUDITED;
        $model->save(false);
        MessageAlert::set(['success' => '审核通过成功！']);
        return $this->actionPreviousRedirect();
    }

    // 作废
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->scenario = ApplyOrder::SCENARIO_DELETE;

        if (!$model->checkStatus(ApplyOrder::STATUS_DELETE)) {
            throw new StatusNotAllowedException();
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->status = ApplyOrder::STATUS_DELETE;
            if ($model->validate() && $model->save(false)) {
                MessageAlert::set(['success' => '作废操作成功！']);
            } else {
                MessageAlert::set(['error' => '作废操作失败：' . Tools::formatModelErrors2String($model->errors)]);
            }
            return $this->actionPreviousRedirect();
        }

        return $this->renderAjax('@view/apply-order/_delete', [
            'model' => $model
        ]);
    }

    // 完成
    public function actionOver($id)
    {
        $applyOrder = $this->findModel($id);

        if ($this->applyOrderType == Enum::APPLY_ORDER_TYPE_RETURN) {
            if (!$applyOrder->checkStatus(ApplyOrder::STATUS_RETURN_OVER)) {
                throw new StatusNotAllowedException();
            }
        } else {
            if (!$applyOrder->checkStatus(ApplyOrder::STATUS_OVER)) {
                throw new StatusNotAllowedException();
            }
        }

        if ($this->applyOrderType == Enum::APPLY_ORDER_TYPE_INPUT) {
            return $this->handleOver($applyOrder, ApplyOrderResource::SCENARIO_INPUT);
        } elseif (in_array($this->applyOrderType, [Enum::APPLY_ORDER_TYPE_OUTPUT, Enum::APPLY_ORDER_TYPE_APPLY])) {
            return $this->handleOver($applyOrder, ApplyOrderResource::SCENARIO_OUTPUT_APPLY);
        } elseif ($this->applyOrderType == Enum::APPLY_ORDER_TYPE_RETURN) {
            return $this->handleOver($applyOrder, ApplyOrderResource::SCENARIO_RETURN);
        }
        throw new Exception('不支持的 APPLY_ORDER_TYPE 类型');
    }

    // 复用
    public function actionClone($id)
    {
        $model = $this->findModel($id);

        $result = $model->cloneOne();
        if ($result['type'] == 'success') {
            MessageAlert::set(['success' => '复制成功']);
        } else {
            MessageAlert::set(['error' => $result['msg']]);
        }

        return $this->actionPreviousRedirect();
    }

    /**
     * @param $id
     * @return ApplyOrder
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = ApplyOrder::findOne(['id' => $id, 'type' => $this->applyOrderType]);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }

    /**
     * 创建和修改申请单
     * @param $applyOrder ApplyOrder
     * @param $applyOrderDetails ApplyOrderDetail[]
     * @return array|string|Response
     */
    protected function handlerCreateAndUpdate($applyOrder, $applyOrderDetails)
    {
        $request = Yii::$app->getRequest();

        if ($request->isPost) {
            $applyOrderDetails = [];
            $data = $request->post('ApplyOrderDetail', []);
            foreach (array_keys($data) as $index) {
                $applyOrderDetails[$index] = new ApplyOrderDetail();
            }
            Model::loadMultiple($applyOrderDetails, $request->post());
            if ($request->post('ajax') !== null) {
                // models 数据的 ajax 验证
                Yii::$app->response->format = Response::FORMAT_JSON;
                $result = ActiveForm::validateMultiple($applyOrderDetails);
                return $result;
            }
            $applyOrder->load($request->post());
            // 检查是否每种资源只有一条记录，确保是汇总数据
            $hasError = false;
            $resourceIds = ArrayHelper::getColumn($applyOrderDetails, 'resource_id');
            if (count($resourceIds) !== count(array_unique($resourceIds))) {
                MessageAlert::set(['error' => '每种资源仅可以添加一条记录']);
                $hasError = true;
            }
            if (!$hasError) {
                // 处理业务
                // 保存申请单
                $applyOrder->save(false);
                // 删除原来的申请单明细
                ApplyOrderDetail::deleteAll(['apply_order_id' => $applyOrder->id]);
                // 保存新的申请单明细
                foreach ($applyOrderDetails as $detail) {
                    /** @var $detail ApplyOrderDetail */
                    $detail->apply_order_id = $applyOrder->id;
                    $detail->save(false);
                }

                MessageAlert::set(['success' => '操作成功']);
                return $this->redirect(['index']);
            }
        }

        return $this->render('@view/apply-order/create_update', [
            'applyOrder' => $applyOrder,
            'applyOrderDetails' => $applyOrderDetails,
        ]);
    }

    /**
     * 完成操作
     * @param $applyOrder ApplyOrder
     * @param $applyOrderResourceScenario
     * @return array|string|Response
     * @throws Exception
     * @throws \yii\db\Exception
     */
    protected function handleOver($applyOrder, $applyOrderResourceScenario)
    {
        $request = Yii::$app->getRequest();

        $applyOrderResourceAttributes = [
            'scenario' => $applyOrderResourceScenario,
            'apply_order_id' => $applyOrder->id,
        ];
        if ($this->applyOrderType == Enum::APPLY_ORDER_TYPE_RETURN) {
            $applyOrderResourceAttributes['is_return'] = 1;
        }

        $applyOrderResources = [new ApplyOrderResource($applyOrderResourceAttributes)];
        $tagSessionAutoStart = true; // 标签检测是否自动开启扫描

        if ($request->isPost) {
            /** @var ApplyOrderResource[] $applyOrderResources */
            $applyOrderResources = [];
            $data = $request->post('ApplyOrderResource', []);
            foreach (array_keys($data) as $index) {
                $applyOrderResources[$index] = new ApplyOrderResource($applyOrderResourceAttributes);
            }
            Model::loadMultiple($applyOrderResources, $request->post());
            if ($request->post('ajax') !== null) {
                // models 数据的 ajax 验证
                Yii::$app->response->format = Response::FORMAT_JSON;
                $result = ActiveForm::validateMultiple($applyOrderResources);
                return $result;
            }
            // 校验数据
            $hasError = false;
            $sourceCountArr = [];
            foreach ($applyOrderResources as $applyOrderResource) {
                $countKey = $applyOrderResource->resource_id;
                if (!isset($sourceCountArr[$countKey])) {
                    $sourceCountArr[$countKey] = $applyOrderResource->quantity;
                } else {
                    $sourceCountArr[$countKey] += $applyOrderResource->quantity;
                }
            }
            $applyOrderDetails = $applyOrder->applyOrderDetails;
            try {
                // 检查实际处理的数量和申请单的数量是否匹配
                foreach ($applyOrderDetails as $applyOrderDetail) {
                    $countKey = $applyOrderDetail->resource_id;
                    if (!isset($sourceCountArr[$countKey])) {
                        throw new Exception("资源'{$applyOrderDetail->resource->name}'不存在");
                    }
                    if ($this->applyOrderType == Enum::APPLY_ORDER_TYPE_RETURN) {
                        if ($sourceCountArr[$countKey] > $applyOrderDetail->quantity_real) {
                            throw new Exception('资源：' . $applyOrderDetail->resource->name . '，实际操作的数量不能大于申领的实际数量');
                        }
                        $applyOrderDetail->quantity_return = $sourceCountArr[$countKey];
                    } else {
                        if ($sourceCountArr[$countKey] > $applyOrderDetail->quantity) {
                            throw new Exception('资源：' . $applyOrderDetail->resource->name . '，实际操作的数量不能大于申请单的数量');
                        }
                        $applyOrderDetail->quantity_real = $sourceCountArr[$countKey];
                    }
                }
            } catch (Exception $e) {
                MessageAlert::set(['error' => $e->getMessage()]);
                $hasError = true;
            }

            if (!$hasError) {
                // 处理业务
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // 保存详情单的实际数量信息
                    foreach ($applyOrderDetails as $applyOrderDetail) {
                        // 实际数量已经在检查实际数量时赋值
                        $applyOrderDetail->save(false);
                    }
                    // 保存资源信息
                    foreach ($applyOrderResources as $applyOrderResource) {
                        $applyOrderResource->save(false);
                        ResourceDetail::operateByApplyOrderType($this->applyOrderType, $applyOrderResource);
                    }
                    // 修改该申请单为已完成
                    if ($this->applyOrderType == Enum::APPLY_ORDER_TYPE_RETURN) {
                        $applyOrder->return_at = time();
                        $applyOrder->status = ApplyOrder::STATUS_RETURN_OVER;
                    } else {
                        $applyOrder->status = ApplyOrder::STATUS_OVER;
                    }
                    $applyOrder->save(false);

                    $transaction->commit();
                    MessageAlert::set(['success' => '操作成功']);
                    return $this->actionPreviousRedirect();
                } catch (Exception $e) {
                    $transaction->rollBack();
                    MessageAlert::set(['error' => '操作失败：' . $e->getMessage()]);
                }
                $tagSessionAutoStart = false;
            }
        }

        $view = 'over';
        if ($applyOrderResourceScenario == ApplyOrderResource::SCENARIO_INPUT) {
            $view = 'over-input';
        }

        return $this->render('@view/apply-order/' . $view, [
            'applyOrder' => $applyOrder,
            'applyOrderResources' => $applyOrderResources,
            'tagSessionAutoStart' => $tagSessionAutoStart,
            'applyOrderResourceScenario' => $applyOrderResourceScenario,
        ]);
    }

    /**
     * 出库和申领完成
     * @param $applyOrder ApplyOrder
     * @return array|string|Response
     * @throws \yii\db\Exception
     */
    protected function handleOverOutputApply($applyOrder)
    {
        $request = Yii::$app->getRequest();
        $applyOrderResources = [new ApplyOrderResource([
            'scenario' => ApplyOrderResource::SCENARIO_OUTPUT_APPLY,
            'apply_order_id' => $applyOrder->id,
        ])];
        $tagSessionAutoStart = true;

        if ($request->isPost) {
            $applyOrderResources = [];
            $data = $request->post('ApplyOrderResource', []);
            foreach (array_keys($data) as $index) {
                $applyOrderResources[$index] = new ApplyOrderResource([
                    'scenario' => ApplyOrderResource::SCENARIO_OUTPUT_APPLY,
                    'apply_order_id' => $applyOrder->id,
                ]);
            }
            Model::loadMultiple($applyOrderResources, $request->post());
            if ($request->post('ajax') !== null) {
                // models 数据的 ajax 验证
                Yii::$app->response->format = Response::FORMAT_JSON;
                $result = ActiveForm::validateMultiple($applyOrderResources);
                return $result;
            }
            // 处理业务
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // 保存资源信息
                foreach ($applyOrderResources as $applyOrderResource) {
                    $applyOrderResource->save(false);
                    ResourceDetail::operateByApplyOrderType($this->applyOrderType, $applyOrderResource);
                }
                // 修改该申请单为已完成
                $applyOrder->status = ApplyOrder::STATUS_OVER;
                $applyOrder->save(false);

                $transaction->commit();
                MessageAlert::set(['success' => '操作成功']);
                return $this->actionPreviousRedirect();
            } catch (Exception $e) {
                $transaction->rollBack();
                if (YII_DEBUG) {
                    MessageAlert::set(['error' => '操作失败：' . $e->getMessage()]);
                } else {
                    throw new Exception($e);
                }
            }
            $tagSessionAutoStart = false;
        }

        return $this->render('@view/apply-order/over-output-apply', [
            'applyOrder' => $applyOrder,
            'applyOrderResources' => $applyOrderResources,
            'tagSessionAutoStart' => $tagSessionAutoStart,
        ]);
    }

}

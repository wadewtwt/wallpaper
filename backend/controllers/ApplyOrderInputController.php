<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\components\MessageAlert;
use backend\exceptions\StatusNotAllowedException;
use backend\models\ApplyOrderSearch;
use common\components\Tools;
use common\models\ApplyOrder;
use common\models\ApplyOrderDetail;
use common\models\Resource;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class ApplyOrderInputController extends AuthWebController
{
    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new ApplyOrderSearch([
            'type' => ApplyOrder::TYPE_INPUT,
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('../apply-order/index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    // 新增
    public function actionCreate()
    {
        $applyOrder = new ApplyOrder();
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

        return $this->render('../apply-order/detail', [
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

        return $this->render('../apply-order/print', [
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

        return $this->renderAjax('../apply-order/_delete', [
            'model' => $model
        ]);
    }

    // 完成
    public function actionOver($id)
    {
        $applyOrder = $this->findModel($id);
        $applyOrderDetails = $applyOrder->applyOrderDetails;

        if (!$applyOrder->checkStatus(ApplyOrder::STATUS_OVER)) {
            throw new StatusNotAllowedException();
        }

        $request = Yii::$app->request;
        if ($request->isPost) {
            Model::loadMultiple($applyOrderDetails, $request->post());
            Model::validateMultiple($applyOrderDetails);
            $rfids = ArrayHelper::getColumn($applyOrderDetails, 'rfid');
            if (in_array('', $rfids)) {
                MessageAlert::set(['error' => '操作失败：RFID 必须填写']);
            } else {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // 数据验证正确后真正的业务
                    foreach ($applyOrderDetails as $applyOrderDetail) {
                        // 保存申请单明细
                        $applyOrderDetail->save(false);
                        // 将明细导入到对应的资源明细表
                        $applyOrderDetail->saveToResource();
                    }
                    // 保存申请单
                    $applyOrder->status = ApplyOrder::STATUS_OVER;
                    $applyOrder->save(false);

                    $transaction->commit();
                    MessageAlert::set(['success' => '操作成功']);
                    return $this->actionPreviousRedirect();
                } catch (Exception $e) {
                    $transaction->rollBack();
                    MessageAlert::set(['error' => '操作失败：' . $e->getMessage()]);
                }
            }
        }

        return $this->render('../apply-order/over', [
            'applyOrder' => $applyOrder,
            'applyOrderDetails' => $applyOrderDetails,
        ]);
    }

    /**
     * @param $id
     * @return ApplyOrder
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = ApplyOrder::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }

    /**
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
            // 装载数据，处理业务
            if ($applyOrder->load($request->post())) {
                // 保存申请单
                $applyOrder->type = ApplyOrder::TYPE_INPUT;
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
                return $this->actionPreviousRedirect();
            }
        }

        // 获取明细资源信息，用于更新或者新增验证未通过时恢复数据
        $resourceData = $this->getResourceData(ArrayHelper::getColumn($applyOrderDetails, 'resource_id'));

        return $this->render('../apply-order/create_update', [
            'applyOrder' => $applyOrder,
            'applyOrderDetails' => $applyOrderDetails,
            'resourceData' => $resourceData
        ]);
    }

    /**
     * 获取资源的 id 和 name 信息
     * @param $resourceIds
     * @return array
     */
    protected function getResourceData($resourceIds)
    {
        return ArrayHelper::map(ArrayHelper::toArray(Resource::find()->select(['id', 'name', 'type'])->where(['id' => $resourceIds])->all(), [
            Resource::className() => [
                'id',
                'nameType' => function (\common\models\Resource $model) {
                    return $model->name . '(' . $model->getTypeName() . ')';
                }
            ]
        ]), 'id', 'nameType');
    }

}

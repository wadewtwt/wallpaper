<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\models\ApplyOrderSearch;
use common\components\Tools;
use common\models\ApplyOrder;
use common\models\ApplyOrderDetail;
use common\models\Device;
use common\models\DeviceDetail;
use common\models\ExpendableDetail;
use Yii;
use yii\web\NotFoundHttpException;
use backend\components\MessageAlert;

class ApplyOrderController extends AuthWebController
{
    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new ApplyOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    // 新增
    public function actionCreate()
    {
        $model = new ApplyOrder();

        if ($postValue = Yii::$app->request->post('ApplyOrder', [])) {
            // apply_order表的插入
            $applOrder = new ApplyOrder();
            $applOrder->type = ApplyOrder::OPERATION_INPUT;
            $applOrder->person_id = $postValue['person_id'];
            $applOrder->reason = $postValue['reason'];
            $applOrder->save(false);

            $applyOrderId = $applOrder->id;// 获得刚插入apply_order那条数据的id

            foreach ($postValue['res'] as $k => $v) {
                // apply_order_detail表
                $applyOrderDetail = new ApplyOrderDetail();
                $applyOrderDetail->apply_order_id = $applyOrderId;
                $applyOrderDetail->resource_id = $v['resourceId'];
                $applyOrderDetail->container_id = $v['containerId'];
                $applyOrderDetail->quantity = $v['quantity'];
                $applyOrderDetail->save(false);
                if ($v['resType'] == ApplyOrder::TABLE_TYPE_EXPENDABLE) {
                    // expendable_detail表
                    $expendableDetail = new ExpendableDetail();
                    $expendableDetail->resource_id = $v['resourceId'];
                    $expendableDetail->container_id = $v['containerId'];
                    $expendableDetail->operation = ApplyOrder::OPERATION_INPUT;
                    $expendableDetail->quantity = $v['quantity'];
                    $expendableDetail->remark = $postValue['reason'];
                    $expendableDetail->created_at = time();// 出入库时间
                    $expendableDetail->scrap_at = 0;// 暂时不定
                    $expendableDetail->save(false);
                } elseif ($v['resType'] == ApplyOrder::TABLE_TYPE_DEVICE) {
                    // device表
                    $device = new Device();
                    $device->resource_id = $v['resourceId'];
                    $device->container_id = $v['containerId'];
                    $device->is_online = 0;// 暂时不定
                    $device->online_change_at = time();
                    $device->maintenance_at = 0;// 暂时不定
                    $device->scrap_at = 0;// 暂时不定
                    $device->quantity = $v['quantity'];
                    $device->save(false);

                    // 获得刚存入device表的id
                    $deviceId = $device->id;
                    // device_detail表
                    $device_detail = new DeviceDetail();
                    $device_detail->device_id = $deviceId;
                    $device_detail->operation = ApplyOrder::OPERATION_INPUT;
                    $device_detail->remark = $postValue['reason'];
                    $device_detail->save(false);
                } else {
                    throw new NotFoundHttpException();
                }
            }

            return $this->actionPreviousRedirect();
        }

        return $this->render('_create_update', [
            'model' => $model
        ]);
    }

    // 打印
    public function actionPrint($id)
    {
        // TODO
    }

    // 修改
    public function actionUpdate($id)
    {
        // TODO
    }

    // 作废
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save(false)) {
                MessageAlert::set(['success' => '作废成功！']);
            } else {
                MessageAlert::set(['error' => '作废失败：' . Tools::formatModelErrors2String($model->errors)]);
            }
            return $this->actionPreviousRedirect();
        }
        return $this->renderAjax('_delete', [
            'model' => $model
        ]);
    }

    // 明细
    public function actionDetail($id)
    {
        // TODO
    }

    // 审核通过
    public function actionPass($id)
    {
        // TODO
    }

    /**
     * @param $id
     * @return null|static|ApplyOrder
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

}

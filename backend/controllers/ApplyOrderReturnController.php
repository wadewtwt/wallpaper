<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\models\ApplyOrderSearch;
use common\models\ApplyOrder;
use Yii;
use yii\web\NotFoundHttpException;
use yii\base\Model;
use backend\components\MessageAlert;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use backend\exceptions\StatusNotAllowedException;
use common\models\Resource;

class ApplyOrderReturnController extends AuthWebController
{
    public function actionIndex()
    {
        $this->rememberUrl();
        // 筛选“借出中”和“已退还”的订单
        $searchModel = new ApplyOrderSearch([
            'status' => [
                ApplyOrder::STATUS_OVER,
                ApplyOrder::STATUS_RETURN_OVER,
            ]
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionReturn($id){
        $this->rememberUrl();

        $applyOrder = $this->findModel($id);
        $applyOrderDetails = $applyOrder->applyOrderDetails;

        $request = Yii::$app->getRequest();

        // 验证状态
        if (!$applyOrder->checkStatus(ApplyOrder::STATUS_RETURN_OVER)) {
            throw new StatusNotAllowedException();
        }

        if ($request->isPost) {
            Model::loadMultiple($applyOrderDetails, $request->post());
            Model::validateMultiple($applyOrderDetails);

            // 过滤，当消耗品的数量为0或者空时,他的rfid可以为空，其他rfid为空的都报错
            foreach ($applyOrderDetails as $applyOrderDetail) {
                if(empty($applyOrderDetail->rfid)){
                    if (($applyOrderDetail->resource->type == Resource::TYPE_EXPENDABLE) && (empty($applyOrderDetail->quantity))){
                        continue;
                    }else{
                        MessageAlert::set(['error' => '操作失败：rfid不能为空']);
                        return $this->actionPreviousRedirect();
                    }
                }
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                // 数据验证正确后真正的业务
                foreach ($applyOrderDetails as $applyOrderDetail) {
                    // 保存申请单明细
                    $applyOrderDetail->save(false);
                    // 将明细导入到对应的资源明细表
                    $applyOrderDetail->solveOrderDetail(ApplyOrder::TYPE_RETURN);
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

        return $this->render('detail',[
            'applyOrder' => $applyOrder,
            'applyOrderDetails' => $applyOrderDetails,
        ]);
    }

    public function actionLook($id){
        $applyOrder = $this->findModel($id);
        $applyOrderDetails = $applyOrder->applyOrderDetails;

        return $this->render('look_detail',[
            'applyOrder' => $applyOrder,
            'applyOrderDetails' => $applyOrderDetails,
        ]);
    }

    protected function findModel($id){
        $model = ApplyOrder::findOne(['id' => $id]);
        if(!$model){
            throw new NotFoundHttpException();
        }
        return $model;
    }
}

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

class ApplyOrderReturnController extends AuthWebController
{
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new ApplyOrderSearch([
            'type' => ApplyOrder::TYPE_RETURN,
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionDetail($id){
        $applyOrder = $this->findModel($id);
        $applyOrderDetails = $applyOrder->applyOrderDetails;

        $request = Yii::$app->getRequest();

        // 验证状态
        if (!$applyOrder->checkStatus(ApplyOrder::STATUS_OPERATE_RETURN)) {
            throw new StatusNotAllowedException();
        }

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
        $model = ApplyOrder::findOne(['id' => $id, 'type' => ApplyOrder::TYPE_RETURN]);
        if(!$model){
            throw new NotFoundHttpException();
        }
        return $model;
    }
}

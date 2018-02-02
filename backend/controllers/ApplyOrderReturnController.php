<?php

namespace backend\controllers;

use backend\models\ApplyOrderReturnSearch;
use common\models\ApplyOrderReturn;
use common\models\base\Enum;
use Yii;
use yii\web\NotFoundHttpException;

class ApplyOrderReturnController extends ApplyOrderController
{
    public $applyOrderType = Enum::APPLY_ORDER_TYPE_RETURN;

    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new ApplyOrderReturnSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionDetailReturn($id)
    {
        $model = $this->findModel($id);

        return $this->render('detail-return', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        $model = ApplyOrderReturn::findOne(['id' => $id]);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}

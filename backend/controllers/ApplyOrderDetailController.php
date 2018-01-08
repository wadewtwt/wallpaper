<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\models\ApplyOrderDetailSearch;
use Yii;

class ApplyOrderDetailController extends AuthWebController
{
    // 列表
    public function actionIndex($id)
    {
        $this->rememberUrl();

        $searchModel = new ApplyOrderDetailSearch($id);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

}

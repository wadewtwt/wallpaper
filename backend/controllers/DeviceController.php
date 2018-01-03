<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\models\DeviceSearch;
use Yii;

class DeviceController extends AuthWebController
{
    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new DeviceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

}

<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\models\DeviceSearch;
use Yii;

class DeviceController extends AuthWebController
{
    // 列表
    public function actionIndex($resource_id = '')
    {
        $this->rememberUrl();

        $searchModel = new DeviceSearch([
            'resource_id' => $resource_id
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

}

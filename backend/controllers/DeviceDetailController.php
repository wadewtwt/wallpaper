<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\models\DeviceDetailSearch;
use Yii;

class DeviceDetailController extends AuthWebController
{
    // 列表
    public function actionIndex($device_id = '')
    {
        $this->rememberUrl();

        $searchModel = new DeviceDetailSearch([
            'device_id' => $device_id
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

}

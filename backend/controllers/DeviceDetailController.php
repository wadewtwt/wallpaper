<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use common\components\ActiveDataProvider;
use common\models\DeviceDetail;

class DeviceDetailController extends AuthWebController
{
    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $dataProvider = new ActiveDataProvider([
            'query' => DeviceDetail::find(),
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'id' => SORT_DESC,
                ]
            ]
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

}

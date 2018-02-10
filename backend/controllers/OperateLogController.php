<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\models\OperateLogSearch;
use Yii;

class OperateLogController extends AuthWebController
{
    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new OperateLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    // 更多
    public function actionMore($id)
    {
        // TODO
    }

}

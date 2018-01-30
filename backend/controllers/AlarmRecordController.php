<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\models\AlarmRecordSearch;
use Yii;

class AlarmRecordController extends AuthWebController
{
    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new AlarmRecordSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    // 新增
    public function actionCreate()
    {
        // TODO
    }

    // 更新
    public function actionUpdate($id)
    {
        // TODO
    }

    // 删除
    public function actionDelete($id)
    {
        // TODO
    }

}

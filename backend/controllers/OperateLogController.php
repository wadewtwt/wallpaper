<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\models\OperateLogSearch;
use common\models\OperateLog;
use Yii;
use yii\web\NotFoundHttpException;

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
        $model = $this->findModel($id);

        return $this->renderAjax('_more', [
            'model' => $model
        ]);
    }

    protected function findModel($id)
    {
        $model = OperateLog::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}

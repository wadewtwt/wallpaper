<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\components\MessageAlert;
use backend\models\AlarmRecordSearch;
use common\components\Tools;
use common\models\AlarmRecord;
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

    // 处理
    public function actionSolve($id)
    {
        $model = AlarmRecord::findOne(['id' => $id, 'status' => AlarmRecord::STATUS_NORMAL]);
        $model->scenario = AlarmRecord::SCENARIO_SOLVE;
        if ($model->load(Yii::$app->request->post())) {
            $model->status = AlarmRecord::STATUS_SOLVED;
            if ($model->validate() && $model->save(false)) {
                MessageAlert::set(['success' => '处理成功']);
            } else {
                MessageAlert::set(['error' => '处理失败：' . Tools::formatModelErrors2String($model->errors)]);
            }
            return $this->actionPreviousRedirect();
        }
        return $this->renderAjax('_solve', [
            'model' => $model
        ]);
    }

}

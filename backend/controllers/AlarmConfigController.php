<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\models\AlarmConfigSearch;
use common\models\AlarmConfig;
use Yii;
use backend\components\MessageAlert;
use yii\web\NotFoundHttpException;
use common\components\Tools;
use yii\base\Exception;
use common\models\AlarmRecord;

class AlarmConfigController extends AuthWebController
{
    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new AlarmConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    // 新增
    public function actionCreate()
    {
        $model = new AlarmConfig();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save(false)) {
                MessageAlert::set(['success' => '添加成功']);
            } else {
                MessageAlert::set(['error' => '添加失败：' . Tools::formatModelErrors2String($model->errors)]);
            }
            return $this->actionPreviousRedirect();
        }
        return $this->renderAjax('_create', [
            'model' => $model
        ]);
    }

    // 删除
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $hasAlarmRecord = AlarmRecord::find()->select(['id'])->where(['alarm_config_id' => $id])->limit(1)->one();
        // 在不在报警记录里面，如果不在，硬删除，如果在，软删除
        if (!$hasAlarmRecord) {
            $isDelete = $model->delete();
        } else {
            $model->status = AlarmConfig::STATUS_DELETE;
            $isDelete = $model->save();
        }
        if ($isDelete) {
            MessageAlert::set(['success' => '删除成功']);
        } else {
            MessageAlert::set(['success' => '删除失败：' . Tools::formatModelErrors2String($model->errors)]);
        }
        return $this->actionPreviousRedirect();
    }

    // 启用、停用
    public function actionChangeStatus($id)
    {
        $model = $this->findModel($id);
        if ($model->status == AlarmConfig::STATUS_NORMAL) {
            $model->status = AlarmConfig::STATUS_STOP;
        } elseif ($model->status == AlarmConfig::STATUS_STOP) {
            $model->status = AlarmConfig::STATUS_NORMAL;
        } else {
            throw new Exception('状态不可修改');
        }
        if ($model->save(false)) {
            MessageAlert::set(['success' => '操作成功']);
        } else {
            MessageAlert::set(['success' => '操作失败：' . Tools::formatModelErrors2String($model->errors)]);
        }
        return $this->actionPreviousRedirect();
    }

    /**
     * @param $id
     * @return AlarmConfig
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = AlarmConfig::findOne(['id' => $id]);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }

}

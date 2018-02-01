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

        $searchModel = new AlarmConfigSearch([
            'status' => [
                AlarmConfig::STATUS_NORMAL,
                AlarmConfig::STATUS_STOP
            ]
        ]);
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
        return $this->renderAjax('_create_update', [
            'model' => $model
        ]);
    }

    // 更新
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save(false)) {
                MessageAlert::set(['success' => '修改成功']);
            } else {
                MessageAlert::set(['error' => '修改失败：' . Tools::formatModelErrors2String($model->errors)]);
            }
            return $this->actionPreviousRedirect();
        }
        return $this->renderAjax('_create_update', [
            'model' => $model
        ]);
    }

    // 删除
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $hasAlarmRecord = AlarmRecord::find()->select(['id'])->where(['alarm_config_id' => $id])->limit(1)->one();
        // 在不在报警记录里面，如果不在，硬删除，如果在，软删除
        if(!$hasAlarmRecord){
            $isDelete = $model->delete();
        }else{
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

    // 启用
    public function actionNormal($id)
    {
        $model = $this->findModel($id);
        if ($model->status == AlarmConfig::STATUS_NORMAL) {
            throw new Exception('请确定该设备的状态');
        }

        $model->status = AlarmConfig::STATUS_NORMAL;
        $isNormal = $model->save();
        if ($isNormal) {
            MessageAlert::set(['success' => '启用成功']);
        } else {
            MessageAlert::set(['success' => '启用失败：' . Tools::formatModelErrors2String($model->errors)]);
        }
        return $this->actionPreviousRedirect();
    }

    // 停用
    public function actionStop($id)
    {
        $model = $this->findModel($id);
        if ($model->status == AlarmConfig::STATUS_STOP) {
            throw new Exception('请确定该设备的状态');
        }

        $model->status = AlarmConfig::STATUS_STOP;
        $isNormal = $model->save();
        if ($isNormal) {
            MessageAlert::set(['success' => '停用成功']);
        } else {
            MessageAlert::set(['success' => '停用失败：' . Tools::formatModelErrors2String($model->errors)]);
        }
        return $this->actionPreviousRedirect();
    }

    public function findModel($id)
    {
        $model = AlarmConfig::findOne(['id' => $id]);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }

}

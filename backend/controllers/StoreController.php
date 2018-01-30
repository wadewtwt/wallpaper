<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\components\MessageAlert;
use common\components\ActiveDataProvider;
use common\components\Tools;
use common\models\Store;
use yii\web\NotFoundHttpException;
use Yii;

class StoreController extends AuthWebController
{
    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $dataProvider = new ActiveDataProvider([
            'query' => Store::find(),
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

    // 新增
    public function actionCreate()
    {
        $model = new Store();
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
        $isDelete = $model->delete();
        if ($isDelete) {
            MessageAlert::set(['success' => '删除成功']);
        } else {
            MessageAlert::set(['success' => '删除失败：' . Tools::formatModelErrors2String($model->errors)]);
        }
        return $this->actionPreviousRedirect();
    }

    public function findModel($id)
    {
        $model = Store::findOne(['id' => $id]);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}

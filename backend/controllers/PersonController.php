<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\models\PersonSearch;
use Yii;
use common\models\Person;
use yii\web\NotFoundHttpException;
use backend\components\MessageAlert;
use common\components\Tools;

class PersonController extends AuthWebController
{
    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new PersonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    // 新增
    public function actionCreate()
    {
        $model = new Person();
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
                MessageAlert::set(['success' => '修改成功！']);
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
        $model->status = Person::STATUS_DELETE;
        $isDelete = $model->save();
        if ($isDelete) {
            MessageAlert::set(['success' => '删除成功']);
        } else {
            MessageAlert::set(['error' => '删除失败：' . Tools::formatModelErrors2String($model->errors)]);
        }
        return $this->actionPreviousRedirect();
    }

    // 已删除人员
    public function actionDeleteIndex()
    {
        $this->rememberUrl();

    }

    /**
     * @param $id
     * @return null|static|Person
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = Person::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}

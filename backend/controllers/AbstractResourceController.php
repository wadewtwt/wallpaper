<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\components\MessageAlert;
use backend\models\ResourceSearch;
use common\components\Tools;
use common\models\Resource;
use Yii;
use yii\web\NotFoundHttpException;

abstract class AbstractResourceController extends AuthWebController
{
    const RESOURCE_TYPE = Resource::TYPE_DEVICE;

    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new ResourceSearch([
            'type' => static::RESOURCE_TYPE
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@view/resource/index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    // 新增
    public function actionCreate()
    {
        $model = new Resource();
        if ($model->load(Yii::$app->request->post())) {
            $model->type = static::RESOURCE_TYPE;
            if ($model->validate() && $model->save(false)) {
                MessageAlert::set(['success' => '添加成功！']);
            } else {
                MessageAlert::set(['error' => '添加失败：' . Tools::formatModelErrors2String($model->errors)]);
            }
            return $this->actionPreviousRedirect();
        }

        $model->loadDefaultValues();
        return $this->renderAjax('@view/resource/_create_update', [
            'model' => $model
        ]);
    }

    // 更新
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save(false)) {
                MessageAlert::set(['success' => '修改成功!']);
            } else {
                MessageAlert::set(['error' => '修改失败：' . Tools::formatModelErrors2String($model->errors)]);
            }
            return $this->actionPreviousRedirect();
        }

        return $this->renderAjax('@view/resource/_create_update', [
            'model' => $model
        ]);
    }

    // 删除
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = Resource::STATUS_DELETED;
        $isDelete = $model->save();
        if ($isDelete) {
            MessageAlert::set(['success' => '删除成功！']);
        } else {
            MessageAlert::set(['success' => '删除失败：' . Tools::formatModelErrors2String($model->errors)]);
        }
        return $this->actionPreviousRedirect();
    }

    /**
     * @param $id
     * @return \common\models\Resource;
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = Resource::findOne(['type' => static::RESOURCE_TYPE, 'id' => $id]);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}

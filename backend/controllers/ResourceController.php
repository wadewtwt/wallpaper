<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\models\ResourceSearch;
use Yii;
use common\models\Resource;
use yii\web\NotFoundHttpException;
use backend\components\MessageAlert;
use common\components\Tools;

class ResourceController extends AuthWebController
{
    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new ResourceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    // 新增
    public function actionCreate()
    {
        $model = new Resource();
        if($model->load(Yii::$app->request->post())){
            if($model->validate() && $model->save(false)){
                MessageAlert::set(['success' => '添加成功']);
            }else{
                MessageAlert::set(['error' => '添加失败：' . Tools::formatModelErrors2String($model->errors)]);
            }
            return $this->actionPreviousRedirect();
        }
        $model->type = Resource::TYPE_RESOURCE;
        return $this->renderAjax('_update_create',[
            'model' => $model
        ]);
    }

    // 更新
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($model->load(Yii::$app->request->post())){
            if ($model->validate() && $model->save()){
                MessageAlert::set(['success' => '修改成功！']);
            }else{
                MessageAlert::set(['error' => '修改失败：'.Tools::formatModelErrors2String($model->errors)]);
            }
            return $this->actionPreviousRedirect();
        }
        $model->type = Resource::TYPE_RESOURCE;
        return $this->renderAjax('_update_create',[
            'model' => $model
        ]);
    }

    // 删除
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = Resource::STATUS_DELETED;
        $isDelete = $model->save();
        if($isDelete){
            MessageAlert::set(['success' => '删除成功']);
        }else{
            MessageAlert::set(['error' => '删除失败：'.Tools::formatModelErrors2String($model->errors)]);
        }
        return $this->actionPreviousRedirect();
    }


    protected function findModel($id)
    {
        $model = Resource::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }

}

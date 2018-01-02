<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\components\MessageAlert;
use backend\models\ResDeviceSearch;
use common\components\Tools;
use common\models\Resource;
use Yii;
use yii\web\NotFoundHttpException;

class ResDeviceController extends AuthWebController
{
    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new ResDeviceSearch();
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
            $model->type = Resource::TYPE_DEVICE;
            if($model->validate() && $model->save(false)){
                MessageAlert::set(['success' => '添加成功！']);
            }else{
                MessageAlert::set(['error' => '添加失败：'.Tools::formatModelErrors2String($model->errors)]);
            }
            return $this->actionPreviousRedirect();
        }
        return $this->renderAjax('_update_create',[
            'model' => $model
        ]);
    }

    // 更新
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($model->load(Yii::$app->request->post())){
            if($model->validate() && $model->save(false)){
                MessageAlert::set(['success' => '修改成功!']);
            }else{
                MessageAlert::set(['error' => '修改失败：'.Tools::formatModelErrors2String($model->errors)]);
            }
            return $this->actionPreviousRedirect();
        }

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
            MessageAlert::set(['success' => '删除成功！']);
        }else{
            MessageAlert::set(['success' => '删除失败：'.Tools::formatModelErrors2String($model->errors)]);
        }
        return $this->actionPreviousRedirect();
    }

    /**
     * @param $id
     * @return \common\models\Resource;
     * @throws NotFoundHttpException
     */
    protected function findModel($id){
        $model = Resource::findOne($id);
        if(!$model){
            throw new NotFoundHttpException();
        }
        return $model;
    }
}

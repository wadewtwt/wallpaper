<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\components\MessageAlert;
use backend\models\PaperSearch;
use common\models\Paper;
use Yii;
use yii\web\NotFoundHttpException;

class PaperController extends AuthWebController
{
    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new PaperSearch(['status'=>Paper::STATUS_NORMAL]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    // 新增
    public function actionCreate()
    {
        $model = new Paper();
        if($model->load(Yii::$app->request->post())){
            if($model->validate() && $model->save(false)){
                MessageAlert::set(['success' => '添加成功']);
            }else{
                MessageAlert::set(['error' => '添加失败']);
            }
            return $this->actionPreviousRedirect();
        }
        return $this->renderAjax('_create_update',[
            'model' => $model
        ]);
    }

    // 更新
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($model->load(Yii::$app->request->post())){
            if($model->validate() && $model->save(false)){
                MessageAlert::set(['success'=>'修改成功']);
            }else{
                MessageAlert::set(['error' => '修改失败']);
            }
            return $this->actionPreviousRedirect();
        }
        return  $this->renderAjax('_create_update',[
            'model' => $model
        ]);
    }

    // 详情
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = Paper::STATUS_DELETED;
        $isDelete = $model->save();
        if($isDelete){
            MessageAlert::set(['success'=>'删除成功']);
        }else{
            MessageAlert::set(['error' => '删除失败']);
        }
        return $this->actionPreviousRedirect();
    }

    /**
     * 注意下面的这个Paper模型需要写上
     * @param $id
     * @return null|static|Paper
     * @throws NotFoundHttpException
     */
    public function findModel($id){
        $model = Paper::findOne($id);
        if(!$model){
            throw new NotFoundHttpException();
        }
        return $model;
    }

}

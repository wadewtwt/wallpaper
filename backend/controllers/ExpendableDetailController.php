<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\components\MessageAlert;
use backend\models\ExpendableDetailSearch;
use common\models\ExpendableDetail;
use Yii;
use common\components\Tools;

class ExpendableDetailController extends AuthWebController
{
    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new ExpendableDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    // 新增
    public function actionCreate()
    {
        $model = new ExpendableDetail();
        if($model->load(Yii::$app->request->post())){
            if($model->validate() && $model->save(false)){
                MessageAlert::set(['success' => '添加成功！']);
            }else{
                MessageAlert::set(['error' => '添加失败：' . Tools::formatModelErrors2String($model->errors)]);
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
        // TODO
    }

    // 删除
    public function actionDelete($id)
    {
        // TODO
    }

}

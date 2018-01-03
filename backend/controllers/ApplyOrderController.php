<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\models\ApplyOrderSearch;
use common\models\ApplyOrder;
use Yii;

class ApplyOrderController extends AuthWebController
{
    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new ApplyOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    // 新增
    public function actionCreate(){
        $model = new ApplyOrder();

        if($model->load(Yii::$app->request->post())){
            if($model->validate() && $model->save(false)){

            }else{

            }
            return $this->actionPreviousRedirect();
        }

        return $this->render('_create_update',[
            'model' => $model
        ]);
    }

    // 打印
    public function actionPrint($id)
    {
        // TODO
    }

    // 修改
    public function actionUpdate($id)
    {
        // TODO
    }

    // 作废
    public function actionDelete($id)
    {
        // TODO
    }

    // 明细
    public function actionDetail($id)
    {
        // TODO
    }

    // 审核通过
    public function actionPass($id)
    {
        // TODO
    }

}

<?php
namespace api\controllers;

use yii\rest\ActiveController;

class BookController extends ActiveController{
    public $modelClass = 'api\models\Book';

    public function actions()
    {
        $action= parent::actions(); // TODO: Change the autogenerated stub
        unset($action['index']);
        unset($action['create']);
        unset($action['update']);
        unset($action['delete']);
    }

    public function actionIndex(){
        return 666;
    }

    public function actionSendEmail(){
        $data['name'] = '小明';
        $data['age'] = 18;
        $data['sex'] = '男';

        return $data;

    }
}
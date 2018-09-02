<?php
namespace frontend\controllers;

use yii\rest\ActiveController;

class BookController extends ActiveController{
    public $modelClass = 'frontend\models\Book';

    public function actionIndex(){
        echo 3333;die;
    }

    public function actionSendEmail(){
        $data['name'] = '小明1111';
        $data['age'] = 18;
        $data['sex'] = '男';
        return $data;
    }
}
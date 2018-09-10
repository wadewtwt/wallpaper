<?php

namespace frontend\controllers;

use common\models\Kinds;
use common\models\Paper;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use QL\QueryList;
use Yii;

class PicController extends ActiveController
{
    public $modelClass = 'frontend\models\Paper';

    public function actionGetMain($num = 20)
    {
        $model = Paper::find()
            ->alias('p')
            ->leftJoin('kinds as k','p.kind=k.id')
            ->select(['p.title', 'p.url', 'k.title as kinds'])
            ->where(['p.status' => Paper::STATUS_NORMAL])
            ->orderBy('p.id desc')
            ->limit(20)
            ->asArray()
            ->all();

        return $model;
    }

}
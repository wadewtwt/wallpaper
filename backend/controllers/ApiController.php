<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use common\models\Container;
use common\models\Resource;
use common\models\ResourceDetail;
use Yii;
use yii\web\Response;

class ApiController extends AuthWebController
{
    public function init()
    {
        parent::init();
        Yii::$app->response->format = Response::FORMAT_JSON;
    }

    public function actionApplyOrderOver($tag_passives)
    {
        $tagPassives = array_filter(explode(',', $tag_passives));
        $models = ResourceDetail::find()
            ->select(['container_id', 'resource_id', 'tag_passive', 'quantity'])
            ->where(['tag_passive' => $tagPassives])
            ->indexBy('tag_passive')
            ->asArray()->all();
        $newData = [];
        foreach ($tagPassives as $tagPassive) {
            $newData[] = isset($models[$tagPassive]) ? $models[$tagPassive] : [
                'tag_passive' => $tagPassive,
                'container_id' => '',
                'resource_id' => '',
                'quantity' => '',
            ];
        }
        return $newData;
    }

    public function actionResourceData()
    {
        return Resource::findAllIdName(null, false);
    }

    public function actionContainerData()
    {
        return Container::findAllIdName(false);
    }
}
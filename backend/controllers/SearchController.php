<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use common\models\Person;
use common\models\Resource;
use Yii;
use yii\web\Response;

class SearchController extends AuthWebController
{
    public function actionResource($q = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $models = Resource::find()->select(['id', 'name', 'type'])
                ->where(['like', 'name', $q])
                ->limit(20)
                ->all();
            $newData = [];
            foreach ($models as $model) {
                $newData[] = [
                    'id' => $model->id,
                    'text' => $model->name . '(' . $model->getTypeName() . ')',
                ];
            }
            $out['results'] = $newData;
        }
        return $out;
    }

    public function actionPerson($q = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $models = Person::find()->select(['id', 'name', 'cellphone'])
                ->where(['like', 'name', $q])
                ->where(['like', 'cellphone', $q])
                ->limit(20)
                ->all();
            $newData = [];
            foreach ($models as $model) {
                $newData[] = [
                    'id' => $model->id,
                    'text' => $model->name . "({$model->cellphone})",
                ];
            }
            $out['results'] = $newData;
        }
        return $out;
    }
}
<?php

namespace backend\controllers;

use common\models\ResourceDetail;
use common\models\Temperature;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class ThirdApiController extends Controller
{
    public function init()
    {
        parent::init();
        $this->enableCsrfValidation = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
    }

    // 更新设备温度
    public function actionTemperatureUpdate($ip, $port, $current)
    {
        $model = Temperature::findOne([
            'ip' => $ip,
            'port' => $port,
        ]);
        if (!$model) {
            return $this->jsonError('未找到设备');
        }
        $model->current = $current;
        $model->current_updated_at = time();
        $model->save(false);
        return $this->jsonOk();
    }

    // 设备触发
    public function actionTagChange($id)
    {
        $tagPassive = $id;
        $model = ResourceDetail::findOne(['tag_passive' => $tagPassive]);
        if (!$model) {
            return $this->jsonError('未找到资源');
        }
        $model->triggerAlarm();
        return $this->jsonOk();
    }

    protected function jsonOk($data = 'ok')
    {
        return [
            'code' => 200,
            'data' => $data,
        ];
    }

    protected function jsonError($msg)
    {
        return [
            'code' => 422,
            'msg' => $msg,
        ];
    }
}
<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use common\models\AlarmRecord;
use common\models\Container;
use common\models\Resource;
use common\models\ResourceDetail;
use Yii;
use yii\helpers\Html;
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

    public function actionAlarmRecords($start_time)
    {
        $models = AlarmRecord::find()
            ->andWhere(['status' => AlarmRecord::STATUS_NORMAL])
            ->andWhere(['>=', 'alarm_at', $start_time])
            ->all();
        // 打开以下测试
        /*$models = AlarmRecord::find()
            ->limit(2)
            ->all();*/
        $records = [];
        if ($models) {
            $records[] = '当前时间:' . date('Y-m-d H:i:s') . '&nbsp;&nbsp;&nbsp;&nbsp;' .  Html::a('查看详情', ['/alarm-record']);
        }
        foreach ($models as $model) {
            $alarmTime = date('Y-m-d H:i:s', $model->alarm_at);
            $records[] = "仓库【{$model->store->name}】报警，{$model->description}，报警时间：{$alarmTime}";
        }
        return $records;
    }
}
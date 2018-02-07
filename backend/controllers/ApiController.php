<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use common\models\AlarmCall;
use common\models\AlarmRecord;
use common\models\Camera;
use common\models\Container;
use common\models\Resource;
use common\models\ResourceDetail;
use common\models\Temperature;
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

    // 申请单完成时的扫描设备
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
            if (isset($models[$tagPassive])) {
                // 只显示在库的设备
                $newData[] = $models[$tagPassive];
            }
        }
        return $newData;
    }

    // 所有资源数据
    public function actionResourceData()
    {
        return Resource::findAllIdName(null, false);
    }

    // 所有货位
    public function actionContainerData()
    {
        return Container::findAllIdName(false);
    }

    // 首页温湿度信息
    public function actionHomeTemperatureData()
    {
        $models = Temperature::find()->with('store')->where(['status' => Temperature::STATUS_NORMAL])->all();
        $data = [];
        foreach ($models as $model) {
            $data[] = [
                'title' => "【{$model->store->name}】{$model->name}",
                'content' => !$model->isLost() ? ('实时:' . $model->current) : '未连接',
                'limit' => '阀值:' . $model->down_limit . '~' . $model->up_limit,
                'is_green' => intval(!$model->isLost() && !$model->isCurrentOutLimit()),
            ];
        }
        return $data;
    }

    // 查看摄像头
    public function actionCameraView()
    {
        $id = Yii::$app->request->post('id');
        $camera = Camera::findOne($id);
        AlarmCall::createByCameraView($camera, '手动查看摄像头');
        return 1;
    }

    // 报警记录
    public function actionAlarmRecords($start_time)
    {
        $models = AlarmRecord::find()
            ->with('store')
            ->andWhere(['status' => AlarmRecord::STATUS_NORMAL])
            ->andWhere(['>=', 'alarm_at', $start_time])
            ->all();
        // 打开以下测试
        /*$models = AlarmRecord::find()
            ->limit(2)
            ->all();*/
        $records = [];
        if ($models) {
            $records[] = '当前时间:' . date('Y-m-d H:i:s') . '&nbsp;&nbsp;&nbsp;&nbsp;' . Html::a('查看详情', ['/alarm-record']);
        }
        foreach ($models as $model) {
            $alarmTime = date('Y-m-d H:i:s', $model->alarm_at);
            $records[] = "仓库【{$model->store->name}】报警，{$model->description}，报警时间：{$alarmTime}";
        }
        return $records;
    }
}
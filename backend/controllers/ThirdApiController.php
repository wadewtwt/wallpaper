<?php

namespace backend\controllers;

use common\models\ResourceDetail;
use common\models\TagActiveUnused;
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
    public function actionTemperatureUpdate($ip, $port, $device_no, $current)
    {
        $model = Temperature::findOne([
            'ip' => $ip,
            'port' => $port,
            'device_no' => $device_no,
        ]);
        if (!$model) {
            return $this->jsonError('未找到设备');
        }
        $model->current = $current;
        $model->current_updated_at = time();
        $model->save(false);
        return $this->jsonOk();
    }

    // 无源标签不在入库等操作时触发出入库报警
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

    // 有源标签当前检测到的数据列表
    public function actionTagActiveList()
    {
        $ids = json_decode(Yii::$app->request->post('ids'), true);
        $usedIds = ResourceDetail::find()->select(['tag_active'])->where([
            'status' => ResourceDetail::$usedStatusData
        ])->column();
        $usedIds = array_filter($usedIds);
        // 当前在线，不在库的，保存到备用库
        $unusedIds = array_diff($ids, $usedIds);
        TagActiveUnused::deleteAll();
        $unusedIdsRows = [];
        foreach ($unusedIds as $id) {
            $unusedIdsRows[] = [$id];
        }
        Yii::$app->db->createCommand()->batchInsert(TagActiveUnused::tableName(), ['tag_active'], $unusedIdsRows)->execute();
        // 当前在线，且在库的，设为在线
        $onlineIds = array_intersect($ids, $usedIds);
        ResourceDetail::updateAll(
            ['is_online' => 1, 'online_change_at' => time()],
            ['status' => ResourceDetail::$usedStatusData, 'tag_active' => $onlineIds, 'is_online' => 0]
        );
        // 在库的，但当前不在线的，设为离线
        $offlineIds = array_diff($usedIds, $ids);
        ResourceDetail::updateAll(
            ['is_online' => 0, 'online_change_at' => time()],
            ['status' => ResourceDetail::$usedStatusData, 'tag_active' => $offlineIds, 'is_online' => 1]
        );

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
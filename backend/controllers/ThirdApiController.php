<?php

namespace backend\controllers;

use backend\models\form\ThirdPartAlarmRecordCreateForm;
use common\components\Tools;
use common\models\ResourceDetail;
use common\models\TagActiveUnused;
use common\models\Temperature;
use Yii;
use yii\base\Exception;
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
        $model->triggerAlarm(false);
        return $this->jsonOk();
    }

    // 有源标签当前检测到的数据列表
    public function actionTagActiveList()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 检测到的
            $ids = array_unique(json_decode(Yii::$app->request->post('ids'), true));
            // 应该在库的
            $storedIds = array_filter(ResourceDetail::find()->select(['tag_active'])->where(['status' => ResourceDetail::STATUS_NORMAL])->column());
            // 被借出的
            $pickedIds = array_filter(ResourceDetail::find()->select(['tag_active'])->where(['status' => ResourceDetail::STATUS_PICKED])->column());
            // 当前在线，不在库且没被借出的，保存到备用库
            $unusedIds = array_diff($ids, $storedIds, $pickedIds);
            // 删除所有
            TagActiveUnused::deleteAll();
            // 保存到备用库
            $unusedIdsRows = [];
            foreach ($unusedIds as $id) {
                $unusedIdsRows[] = [$id];
            }
            Yii::$app->db->createCommand()->batchInsert(TagActiveUnused::tableName(), ['tag_active'], $unusedIdsRows)->execute();
            // 原来离线，当前在线，且在库的，设为在线
            $onlineIds = array_intersect($ids, $storedIds);
            ResourceDetail::updateAll(
                ['is_online' => 1, 'online_change_at' => time()],
                ['is_online' => 0, 'tag_active' => $onlineIds, 'status' => ResourceDetail::STATUS_NORMAL]
            );
            // 原来在线，且在库的，但当前不在线的，设为离线，且报警
            $offlineIds = array_diff($storedIds, $ids);
            $alarmResources = ResourceDetail::findAll(['is_online' => 1, 'tag_active' => $offlineIds, 'status' => ResourceDetail::STATUS_NORMAL]);
            ResourceDetail::updateAll(
                ['is_online' => 0, 'online_change_at' => time()],
                ['is_online' => 1, 'tag_active' => $offlineIds, 'status' => ResourceDetail::STATUS_NORMAL]
            );
            foreach ($alarmResources as $alarmResource) {
                $alarmResource->triggerAlarm(true);
            }

            $transaction->commit();
            return $this->jsonOk();
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error($e);
            return $this->jsonError($e->getMessage());
        }
    }

    // 创建报警记录
    public function actionAlarmRecordCreate()
    {
        $model = new ThirdPartAlarmRecordCreateForm();

        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            $result = $model->create();
            if ($result['type'] == 'success') {
                return $this->jsonOk($result['msg']);
            } else {
                return $this->jsonError($result['msg']);
            }
        }

        return $this->jsonError(Tools::getFirstError($model->errors));
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
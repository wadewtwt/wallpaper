<?php
/** @var $this yii\web\view */
/** @var $model backend\models\AlarmRecordSearch */

use backend\widgets\SimpleSearchForm;
use common\models\AlarmRecord;
use common\models\Store;
use common\models\Camera;
use common\models\AlarmConfig;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'store_id')->dropDownList(Store::findAllIdName(true), ['prompt' => '全部']);
echo $form->field($model, 'camera_id')->dropDownList(Camera::findAllIdName(true), ['prompt' => '全部'])->label('摄像头');
echo $form->field($model, 'type')->dropDownList(AlarmConfig::$typeData, ['prompt' => '全部']);;
echo $form->field($model, 'status')->dropDownList(AlarmRecord::$statusData, ['prompt' => '全部']);;

echo $form->renderFooterButtons();

$form->end();

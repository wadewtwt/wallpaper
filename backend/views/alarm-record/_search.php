<?php
/** @var $this yii\web\view */
/** @var $model backend\models\AlarmRecordSearch */

use backend\widgets\SimpleSearchForm;
use common\models\Store;
use common\models\Camera;
use common\models\AlarmConfig;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'alarm_config_id');
echo $form->field($model, 'store_id')->dropDownList(Store::findAllIdName(true), ['prompt' => '全部']);
echo $form->field($model, 'camera_id')->dropDownList(Camera::findAllIdName(true), ['prompt' => '全部']);
echo $form->field($model, 'type')->dropDownList(AlarmConfig::$typeData, ['prompt' => '全部']);;

echo $form->renderFooterButtons();

$form->end();

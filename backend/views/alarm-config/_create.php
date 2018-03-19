<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\Store */

use backend\widgets\SimpleAjaxForm;
use common\models\Store;
use common\models\Camera;
use common\models\AlarmConfig;

$form = SimpleAjaxForm::begin(['header' => $model->isNewRecord ? '新增' : '编辑']);
echo $form->field($model, 'store_id')->dropDownList(Store::findAllIdName(true),['prompt' => '请选择']);
echo $form->field($model, 'camera_id')->dropDownList(Camera::findAllIdName(true),['prompt' => '请选择']);
echo $form->field($model, 'type')->dropDownList(AlarmConfig::$typeData,['prompt' => '请选择']);
echo $form->field($model, 'remark')->textarea();
SimpleAjaxForm::end();
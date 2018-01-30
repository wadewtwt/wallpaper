<?php
/** @var $this yii\web\view */
/** @var $model backend\models\TemperatureSearch */

use backend\widgets\SimpleSearchForm;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'store_id');
echo $form->field($model, 'ip');
echo $form->field($model, 'port');
echo $form->field($model, 'device_no');

echo $form->renderFooterButtons();

$form->end();

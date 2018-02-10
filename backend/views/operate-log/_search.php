<?php
/** @var $this yii\web\view */
/** @var $model backend\models\OperateLogSearch */

use backend\widgets\SimpleSearchForm;
use kriss\widgets\DateRange4Form;
use kriss\widgets\DateControl;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'route');

echo DateRange4Form::widget([
    'form' => $form,
    'model' => $model,
    'attribute1' => 'start_time',
    'attribute2' => 'end_time',
    'displayType' => DateControl::FORMAT_DATETIME,
    'label' => '开始时间-截止时间'
]);
echo $form->field($model, 'created_by')->dropDownList(\common\models\Admin::findAllIdName(true), [
    'prompt' => '全部'
]);

echo $form->renderFooterButtons();

$form->end();

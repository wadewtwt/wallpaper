<?php
/** @var $this \yii\web\View */
/** @var $model \backend\models\form\AlarmRecordBatchSolveForm */

use backend\widgets\SimpleAjaxForm;
use common\models\Admin;
use common\models\AlarmRecord;
use yii\helpers\Html;

$form = SimpleAjaxForm::begin(['header' => '批量处理']);
echo Html::activeHiddenInput($model, 'keys');
echo $form->field($model, 'solve_id')->dropDownList(Admin::findAllIdName(true), ['prompt' => '请选择']);
echo $form->field($model, 'solve_description')->textarea(['row' => 4, 'placeholder' => '不填默认为' . AlarmRecord::SOLVE_DESCRIPTION_DEFAULT]);
SimpleAjaxForm::end();
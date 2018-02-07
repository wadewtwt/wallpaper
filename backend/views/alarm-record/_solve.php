<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\AlarmRecord */

use backend\widgets\SimpleAjaxForm;
use common\models\Admin;
use common\models\AlarmRecord;

$form = SimpleAjaxForm::begin(['header' => '处理']);
echo $form->field($model, 'solve_id')->dropDownList(Admin::findAllIdName(true), ['prompt' => '请选择']);
echo $form->field($model, 'solve_description')->textarea(['row' => 4, 'placeholder' => '不填默认为' . AlarmRecord::SOLVE_DESCRIPTION_DEFAULT]);
SimpleAjaxForm::end();
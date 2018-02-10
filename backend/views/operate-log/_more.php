<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\OperateLog */

use backend\widgets\SimpleAjaxForm;
use common\models\Admin;

$form = SimpleAjaxForm::begin(['header' => '更多信息']);

echo $form->field($model, 'created_at')->textInput(['readonly' => true, 'value' => date("Y-m-d H:i:s", $model->created_at)]);
echo $form->field($model, 'created_by')->dropDownList(Admin::findAllIdName(true), ['readonly' => true]);
echo $form->field($model, 'route')->textInput(['readonly' => true]);
echo $form->field($model, 'absolute_url')->textInput(['readonly' => true]);
echo $form->field($model, 'method')->textInput(['readonly' => true]);

$form->renderSubmit = false;
$form->cancelLabel = '返回';
SimpleAjaxForm::end();

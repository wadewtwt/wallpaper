<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\OperateLog */

use backend\widgets\SimpleAjaxForm;
use common\models\Admin;

$form = SimpleAjaxForm::begin(['header' => '更多信息','renderSubmit'=>false,'cancelLabel' => '返回']);

echo $form->field($model, 'created_at')->textInput(['readonly' => true, 'value' => date("Y-m-d H:i:s", $model->created_at)]);
echo $form->field($model, 'created_by')->dropDownList(Admin::findAllIdName(true), ['readonly' => true]);
echo $form->field($model, 'route')->textInput(['readonly' => true]);
echo $form->field($model, 'absolute_url')->textInput(['readonly' => true]);
echo $form->field($model, 'method')->textInput(['readonly' => true]);
echo $form->field($model, 'referrer')->textarea(['readonly' => true, 'rows' => '4']);
echo $form->field($model, 'user_ip')->textInput(['readonly' => true]);
echo $form->field($model, 'user_agent')->textInput(['readonly' => true]);
echo $form->field($model, 'raw_body')->textarea(['readonly' => true, 'rows' => '4']);
echo $form->field($model, 'query_string')->textInput(['readonly' => true]);

SimpleAjaxForm::end();

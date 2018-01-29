<?php
/** @var $this \yii\web\View */

use backend\widgets\SimpleAjaxForm;

$isEdit = ($model->isNewRecord) ? 0 : 1;
$form = SimpleAjaxForm::begin(['header' => '新增']);

echo $form->field($model, 'name');
echo $form->field($model, 'total_quantity');
echo $form->field($model, 'free_quantity');

SimpleAjaxForm::end();
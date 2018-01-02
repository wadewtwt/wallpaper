<?php
/** @var $this \yii\web\View */

use backend\widgets\SimpleAjaxForm;
$form = SimpleAjaxForm::begin(['header' => ($model->isNewRecord) ? '新增' : '编辑']);
echo $form->field($model, 'name');
echo $form->field($model, 'min_stock');
echo $form->field($model, 'current_stock');
echo $form->field($model, 'scrap_cycle');
echo $form->field($model, 'maintenance_cycle');
SimpleAjaxForm::end();
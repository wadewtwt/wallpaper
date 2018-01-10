<?php
/** @var $this \yii\web\View */

use backend\widgets\SimpleAjaxForm;

$form = SimpleAjaxForm::begin(['header' => ($model->isNewRecord) ? '新增' : '编辑']);
echo $form->field($model, 'name');
echo $form->field($model, 'min_stock');
echo $form->field($model, 'current_stock')->textInput(['readonly'=>'true']);
echo $form->field($model, 'scrap_cycle')->textInput(['readonly'=>'true']);
SimpleAjaxForm::end();
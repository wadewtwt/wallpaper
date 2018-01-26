<?php
/** @var $this \yii\web\View */

use backend\widgets\SimpleAjaxForm;

$isEdit = ($model->isNewRecord) ? 0 : 1;
$form = SimpleAjaxForm::begin(['header' => $isEdit ? '编辑' : '新增']);
echo $form->field($model, 'name');
echo $form->field($model, 'min_stock');
if($isEdit){
    echo $form->field($model, 'current_stock')->textInput(['readonly'=>'true']);
    echo $form->field($model, 'scrap_cycle')->textInput(['readonly'=>'true']);
}else{
    echo $form->field($model, 'current_stock');
    echo $form->field($model, 'scrap_cycle');
}

SimpleAjaxForm::end();
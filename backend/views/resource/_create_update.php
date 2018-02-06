<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\Resource */

use backend\widgets\SimpleAjaxForm;
use common\models\ResourceType;

$isEdit = ($model->isNewRecord) ? 0 : 1;
$form = SimpleAjaxForm::begin(['header' => $isEdit ? '编辑' : '新增']);
echo $form->field($model, 'resource_type_id')->dropDownList(ResourceType::findAllIdName(true), [
    'prompt' => '请选择'
]);
echo $form->field($model, 'name');
echo $form->field($model, 'min_stock');
if ($isEdit) {
    echo $form->field($model, 'current_stock')->textInput(['readonly' => 'true']);
    echo $form->field($model, 'scrap_cycle')->textInput(['readonly' => 'true']);
    echo $form->field($model, 'maintenance_cycle')->textInput(['readonly' => 'true']);
} else {
    echo $form->field($model, 'scrap_cycle');
    echo $form->field($model, 'maintenance_cycle');
}

SimpleAjaxForm::end();
<?php
/** @var $this \yii\web\View */

use backend\widgets\SimpleAjaxForm;
use common\models\Store;

$isEdit = ($model->isNewRecord) ? 0 : 1;
$form = SimpleAjaxForm::begin(['header' => '新增']);

echo $form->field($model, 'store_id')->dropDownList(Store::findAllIdName(true), [
    'prompt' => '请选择'
]);
echo $form->field($model, 'name');
echo $form->field($model, 'total_quantity');
echo $form->field($model, 'remark')->textarea([
    'rows' => 4
]);

SimpleAjaxForm::end();
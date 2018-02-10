<?php
/** @var $this yii\web\view */
/** @var $model backend\models\OperateLogSearch */

use backend\widgets\SimpleSearchForm;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'route');
echo $form->field($model, 'created_at');
echo $form->field($model, 'created_by')->dropDownList(\common\models\Admin::findAllIdName(true), [
    'prompt' => '全部'
]);

echo $form->renderFooterButtons();

$form->end();

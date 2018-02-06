<?php
/** @var $this yii\web\view */
/** @var $model backend\models\ResourceSearch */

use backend\widgets\SimpleSearchForm;
use common\models\ResourceType;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'resource_type_id')->dropDownList(ResourceType::findAllIdName(true), [
    'prompt' => '全部'
]);
echo $form->field($model, 'name');

echo $form->renderFooterButtons();

$form->end();

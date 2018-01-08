<?php
/** @var $this yii\web\view */
/** @var $model backend\models\ApplyOrderDetailSearch */

use backend\widgets\SimpleSearchForm;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'apply_order_id');
echo $form->field($model, 'resource_id');
echo $form->field($model, 'container_id');

echo $form->renderFooterButtons();

$form->end();

<?php
/** @var $this yii\web\view */
/** @var $model backend\models\ResourceDetailOperationSearch */

use backend\widgets\SimpleSearchForm;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'resource_detail_id');

echo $form->renderFooterButtons();

$form->end();

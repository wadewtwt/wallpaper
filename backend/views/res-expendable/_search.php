<?php
/** @var $this yii\web\view */
/** @var $model backend\models\ResExpendableSearch */

use backend\widgets\SimpleSearchForm;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'name');

echo $form->renderFooterButtons();

$form->end();

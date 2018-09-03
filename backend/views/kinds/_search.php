<?php
/** @var $this yii\web\view */
/** @var $model backend\models\KindsSearch */

use backend\widgets\SimpleSearchForm;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'title');
echo $form->field($model, 'type');

echo $form->renderFooterButtons();

$form->end();

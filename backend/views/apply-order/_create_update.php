<?php
/** @var $this \yii\web\View */

use backend\widgets\SimpleActiveForm;

$form = SimpleActiveForm::begin();
echo $form->field($model,'type');
SimpleActiveForm::end();
<?php
/** @var $this yii\web\view */
/** @var $model backend\models\PaperSearch */

use backend\widgets\SimpleSearchForm;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'title');
echo $form->field($model, 'url');
echo $form->field($model, 'type');
echo $form->field($model, 'kind');
echo $form->field($model, 'introduction');
echo $form->renderFooterButtons();

$form->end();

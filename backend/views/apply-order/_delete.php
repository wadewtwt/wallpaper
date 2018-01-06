<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\ApplyOrder */

use backend\widgets\SimpleAjaxForm;

$form = SimpleAjaxForm::begin(['header' => '作废']);

echo $form->field($model, 'delete_reason')->textarea(['rows' => 4]);

SimpleAjaxForm::end();
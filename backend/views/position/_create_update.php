<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\Position */

use backend\widgets\SimpleAjaxForm;

$form = SimpleAjaxForm::begin(['header' => ($model->isNewRecord) ? '新增' : '修改']);

echo $form->field($model, 'name');

SimpleAjaxForm::end();
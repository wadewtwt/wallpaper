<?php
/** @var $thid \yii\web\View */
/** @var $model \common\models\Store */

use backend\widgets\SimpleAjaxForm;

$form = SimpleAjaxForm::begin(['header' => $model->isNewRecord ? '新增' : '编辑']);
echo $form->field($model, 'name');
echo $form->field($model, 'remark');
SimpleAjaxForm::end();
<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\ResourceType */

use backend\widgets\SimpleAjaxForm;

$form = SimpleAjaxForm::begin(['header' => ($model->isNewRecord) ? '新增' : '编辑']);
echo $form->field($model, 'name');
SimpleAjaxForm::end();
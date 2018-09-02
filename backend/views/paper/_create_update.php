<?php
/** @var \yii\web\View */

/** @var \common\models\Paper */

use kriss\widgets\SimpleAjaxForm;

$form = SimpleAjaxForm::begin(['header' => ($model->isNewRecord) ? '新增' : '修改']);

echo $form->field($model, 'title');
echo $form->field($model, 'url');
echo $form->field($model, 'type');
echo $form->field($model, 'introduction')->textarea(['rows'=>4]);


SimpleAjaxForm::end();
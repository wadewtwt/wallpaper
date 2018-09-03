<?php
use backend\widgets\SimpleAjaxForm;

$form = SimpleAjaxForm::begin(['header'=>($model->isNewRecord)?'新增':'修改']);
echo $form->field($model,'title');
echo $form->field($model,'type')->label('几级类型');
echo $form->field($model,'pid')->label('父id');
SimpleAjaxForm::end();
"<?php

use backend\widgets\SimpleAjaxForm;

$form = SimpleAjaxForm::begin(['header' => '作废']);
echo $form->field($model, 'delete_reason');
SimpleAjaxForm::end();
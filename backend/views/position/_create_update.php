<?php
/** @var $this \yii\web\View */
/** @var $model Position */

use backend\widgets\SimpleAjaxForm;
use common\models\Position;

$position = Position::findIdName(true);

$form = SimpleAjaxForm::begin(['header' => ($model->isNewRecord) ? '新增' : '修改']);
echo $form->field($model, 'name');
SimpleAjaxForm::end();
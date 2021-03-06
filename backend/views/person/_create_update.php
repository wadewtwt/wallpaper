<?php
/** @var $this \yii\web\View */
/** @var $model Position */

use backend\widgets\SimpleAjaxForm;
use common\models\Position;

$positions = Position::findAllIdName(true);

$form = SimpleAjaxForm::begin(['header' => ($model->isNewRecord) ? '新增' : '修改']);
echo $form->field($model, 'name');
echo $form->field($model, 'cellphone');
echo $form->field($model, 'position_id')->dropDownList($positions, ['prompt' => '请选择职称']);
SimpleAjaxForm::end();
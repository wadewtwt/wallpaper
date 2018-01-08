<?php
/** @var $this yii\web\view */
/** @var $model backend\models\PersonSearch */

use backend\widgets\SimpleSearchForm;
use common\models\Position;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'name');
echo $form->field($model, 'cellphone');
echo $form->field($model,'position_id')->dropDownList(Position::findAllIdName(true),[
    'prompt' => '请选择'
]);
echo $form->renderFooterButtons();

$form->end();

<?php
/** @var $this yii\web\view */
/** @var $model backend\models\ApplyOrderSearch */

use backend\widgets\SimpleSearchForm;
use common\models\ApplyOrder;
use common\models\Person;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'person_id')->dropDownList(Person::findAllIdName(true), [
    'prompt' => '请选择'
])->label('申请人');

echo $form->field($model, 'status')->dropDownList(ApplyOrder::$statusData, [
    'prompt' => '请选择'
]);

echo $form->renderFooterButtons();

$form->end();

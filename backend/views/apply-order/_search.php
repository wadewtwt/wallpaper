<?php
/** @var $this yii\web\view */
/** @var $model backend\models\ApplyOrderSearch */

use backend\widgets\SimpleSearchForm;
use common\models\Person;
use common\models\ApplyOrder;

$form = SimpleSearchForm::begin(['action' => ['index']]);
echo $form->field($model, 'person_id')->dropDownList(Person::findIdName(true), [
    'prompt' => '请选择'
])->label('申请人');
echo $form->field($model, 'type')->radioList([ApplyOrder::OPERATION_INPUT => '申请入库']);

echo $form->renderFooterButtons();

$form->end();

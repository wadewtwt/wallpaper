<?php
/** @var $this yii\web\view */
/** @var $model backend\models\ResourceDetailOperationSearch */

use backend\widgets\SimpleDatetimeRange;
use backend\widgets\SimpleSearchForm;
use backend\widgets\SimpleSelect2;
use common\models\Person;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'resource_detail_id');
echo $form->field($model, 'person_id')->widget(SimpleSelect2::className(), [
    'data' => Person::findAllIdName(true),
    'defaultOptions' => ['prompt' => '全部']
]);
echo SimpleDatetimeRange::widget([
    'form' => $form,
    'model' => $model,
    'attribute1' => 'created_at_1',
    'attribute2' => 'created_at_2',
]);

echo $form->renderFooterButtons();

$form->end();

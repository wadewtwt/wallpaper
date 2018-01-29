<?php
/** @var $this yii\web\view */
/** @var $model backend\models\ExpendableDetailSearch */

use backend\widgets\SimpleSearchForm;
use common\models\ExpendableDetail;
use common\models\Resource;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'operation')->dropDownList(ExpendableDetail::$operationData, [
    'prompt' => '全部'
]);
echo $form->field($model, 'resource_id')->dropDownList(Resource::findAllIdName(Resource::TYPE_EXPENDABLE, true), [
    'prompt' => '全部'
])->label('消耗品名称');

echo $form->renderFooterButtons();

$form->end();

<?php
/** @var $this yii\web\view */
/** @var $model backend\models\ExpendableDetailSearch */

use backend\widgets\SimpleSearchForm;
use common\models\ExpendableDetail;
use common\models\Resource;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'operation')->radioList(ExpendableDetail::$stockOperation, []);
echo $form->field($model, 'resource_id')->dropDownList(Resource::findExpendableDevice(true), [
    'prompt' => '请选择消耗品'
])->label('消耗品名称');

echo $form->renderFooterButtons();

$form->end();

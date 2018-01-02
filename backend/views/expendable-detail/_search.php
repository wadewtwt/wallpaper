<?php
/** @var $this yii\web\view */
/** @var $model backend\models\ExpendableDetailSearch */

use backend\widgets\SimpleSearchForm;
use common\models\ExpendableDetail;
use common\models\Resource;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'operation')->radioList(ExpendableDetail::$stockOperation, []);
echo $form->field($model, 'resource_id')->dropDownList(Resource::findExpendableDevice(), [
    'prompt' => '请选择消耗品'
]);

echo $form->renderFooterButtons();

$form->end();

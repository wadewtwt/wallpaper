<?php
/** @var $this yii\web\view */
/** @var $model backend\models\ResourceDetailSearch */

use backend\widgets\SimpleSearchForm;
use common\models\Resource;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'resource_id')->dropDownList(Resource::findAllIdName(Resource::TYPE_DEVICE, true),[
    'prompt' => '请选择'
])->label('资源名称');
echo $form->renderFooterButtons();

$form->end();

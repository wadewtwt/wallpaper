<?php
/** @var $this yii\web\view */
/** @var $model backend\models\DeviceSearch */

use backend\widgets\SimpleSearchForm;
use common\models\Resource;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'resource_id')->dropDownList(Resource::findAllIdName(Resource::TYPE_DEVICE, true),[
    'prompt' => '请选择设备'
])->label('设备名称');
echo $form->renderFooterButtons();

$form->end();

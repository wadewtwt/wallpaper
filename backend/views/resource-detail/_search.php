<?php
/** @var $this yii\web\view */
/** @var $model backend\models\ResourceDetailSearch */

use backend\widgets\SimpleSearchForm;
use common\models\Resource;
use common\models\ResourceDetail;

$form = SimpleSearchForm::begin(['action' => ['index']]);

echo $form->field($model, 'resource_id')->dropDownList(Resource::findAllIdName(Resource::TYPE_DEVICE, true),[
    'prompt' => '全部'
])->label('资源');
echo $form->field($model, 'status')->dropDownList(ResourceDetail::$statusData,[
    'prompt' => '全部'
]);
echo $form->field($model, 'is_online')->dropDownList([
    1 => '在线',
    0 => '离线'
],[
    'prompt' => '全部'
]);
echo $form->renderFooterButtons();

$form->end();

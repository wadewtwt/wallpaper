<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\Temperature */

use backend\widgets\SimpleActiveForm;
use common\models\Store;

$this->title = $model->isNewRecord ? '新增' : '编辑';
$this->params['breadcrumbs'] = [
    '摄像头设备管理',
    $this->title
];

$form = SimpleActiveForm::begin([
    'title' => $this->title
]);
echo $form->field($model, 'store_id')->dropDownList(Store::findAllIdName(true),['prompt' => '选择仓库']);
echo $form->field($model, 'ip');
echo $form->field($model, 'port');
echo $form->field($model, 'username');
echo $form->field($model, 'password');
echo $form->field($model, 'name');
echo $form->field($model, 'device_no');
echo $form->field($model, 'remark');
echo $form->field($model, 'status');
echo $form->renderFooterButtons();
SimpleActiveForm::end();
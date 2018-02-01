<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\Temperature */

use backend\widgets\SimpleAjaxForm;
use common\models\Store;

$form = SimpleAjaxForm::begin(['header' => ($model->isNewRecord) ? '新增' : '编辑']);
echo $form->field($model, 'store_id')->dropDownList(Store::findAllIdName(true), ['prompt' => '选择仓库']);
echo $form->field($model, 'ip');
echo $form->field($model, 'port');
echo $form->field($model, 'username');
echo $form->field($model, 'password');
echo $form->field($model, 'name');
echo $form->field($model, 'device_no');
echo $form->field($model, 'remark')->textarea(['row' => 4]);
SimpleAjaxForm::end();
<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\Temperature */

use backend\widgets\SimpleAjaxForm;
use common\models\Admin;

$form = SimpleAjaxForm::begin(['header' => ($model->isNewRecord) ? '新增' : '编辑']);
echo $form->field($model, 'solve_id')->dropDownList(Admin::findAllIdName(true), ['prompt' => '选择处理人']);
echo $form->field($model, 'solve_description');
SimpleAjaxForm::end();
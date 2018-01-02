<?php
/** @var $this \yii\web\View */

use common\widgets\SimpleAjaxForm;
use yii\helpers\Html;

$form = SimpleAjaxForm::begin(['header' => ($model->isNewRecord) ? '新增' : '编辑']);
echo Html::activeHiddenInput($model, 'type');
echo $form->field($model, 'name');
echo $form->field($model, 'min_stock');
echo $form->field($model, 'current_stock');
echo $form->field($model, 'scrap_cycle');
SimpleAjaxForm::end();
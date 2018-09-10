<?php
/** @var \yii\web\View */

/** @var \common\models\Paper */

use kriss\widgets\SimpleAjaxForm;
use common\models\Kinds;

$kinds = Kinds::findAllIdTitle(true);
$form = SimpleAjaxForm::begin(['header' => ($model->isNewRecord) ? '新增' : '修改']);

echo $form->field($model, 'title');
echo $form->field($model, 'url');
echo $form->field($model, 'kind')->dropDownList($kinds,['prompt'=>'请选择类型']);
echo $form->field($model, 'introduction')->textarea(['rows'=>4]);


SimpleAjaxForm::end();
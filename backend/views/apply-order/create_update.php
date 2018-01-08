<?php
/** @var $this \yii\web\View */
/** @var $applyOrder ApplyOrder */
/** @var $applyOrderDetails \common\models\ApplyOrderDetail[] */

/** @var $resourceData array */

use backend\widgets\SimpleActiveForm;
use common\models\ApplyOrder;
use common\models\Container;
use common\models\Person;
use common\models\Resource;
use kartik\select2\Select2;
use unclead\multipleinput\TabularColumn;
use unclead\multipleinput\TabularInput;
use yii\helpers\Url;

$form = SimpleActiveForm::begin();

$resourceSearchUrl = Url::to(['/search/resource']);
if (in_array($applyOrder->type, [ApplyOrder::TYPE_APPLY, ApplyOrder::TYPE_RETURN])) {
    $resourceSearchUrl = Url::to(['/search/resource', 'type' => Resource::TYPE_DEVICE]);
}

echo $form->field($applyOrder, 'person_id')->dropDownList(Person::findAllIdName(true), [
    'prompt' => '请选择'
])->label('申请人');
echo $form->field($applyOrder, 'reason')->textarea(['rows' => 4]);
if ($applyOrder->type == ApplyOrder::TYPE_APPLY) {
    echo $form->field($applyOrder, 'pick_type')->dropDownList(ApplyOrder::$pickTypeData);
}

echo TabularInput::widget([
    'models' => $applyOrderDetails,
    'attributeOptions' => [
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
        'validateOnChange' => false,
        'validateOnSubmit' => true,
        'validateOnBlur' => false,
    ],
    'columns' => [
        [
            'name' => 'resource_id',
            'title' => '资源名',
            'enableError' => true,
            'type' => Select2::className(),
            'options' => [
                'data' => $resourceData,
                'options' => ['placeholder' => '请输入资源名称'],
                'language' => 'zh-CN',
                'theme' => 'default',
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'ajax' => ['url' => $resourceSearchUrl,],
                ],
            ],
        ],
        [
            'name' => 'container_id',
            'title' => '货位',
            'enableError' => true,
            'type' => TabularColumn::TYPE_DROPDOWN,
            'items' => Container::findAllIdName(true, true),
            'options' => [
                'prompt' => '请选择'
            ]
        ],
        [
            'name' => 'rfid',
            'title' => 'RFID',
            'enableError' => true,
        ],
        [
            'name' => 'quantity',
            'title' => '数量',
            'enableError' => true,
            'defaultValue' => 1,
        ],

    ],
]);

echo $form->renderFooterButtons();

SimpleActiveForm::end();
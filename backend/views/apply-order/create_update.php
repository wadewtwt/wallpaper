<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\ApplyOrder */
/** @var $models \common\models\ApplyOrderDetail[] */
/** @var $resourceData array */

use backend\widgets\SimpleActiveForm;
use common\models\Container;
use common\models\Person;
use kartik\select2\Select2;
use unclead\multipleinput\TabularColumn;
use unclead\multipleinput\TabularInput;
use yii\helpers\Url;

$form = SimpleActiveForm::begin();

echo $form->field($model, 'person_id')->dropDownList(Person::findAllIdName(true), [
    'prompt' => '请选择'
])->label('申请人');
echo $form->field($model, 'reason')->textarea(['rows' => 4]);

echo TabularInput::widget([
    'models' => $models,
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
                    'ajax' => [
                        'url' => Url::to(['/search/resource']),
                    ],
                ],
            ],
        ],
        [
            'name' => 'container_id',
            'title' => '货位',
            'type' => TabularColumn::TYPE_DROPDOWN,
            'items' => Container::findAllIdName(true, true),
            'options' => [
                'prompt' => '请选择'
            ]
        ],
        [
            'name' => 'rfid',
            'title' => 'RFID',
        ],
        [
            'name' => 'quantity',
            'title' => '数量',
            'defaultValue' => 1,
        ],

    ],
]);

echo $form->renderFooterButtons();

SimpleActiveForm::end();
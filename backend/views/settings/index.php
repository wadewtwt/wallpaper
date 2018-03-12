<?php
/** @var $this \yii\web\View */
/** @var $settings \common\models\Settings[] */

use kriss\widgets\SimpleActiveForm;
use common\models\Settings;

$this->title = '参数设置';
$this->params['breadcrumbs'] = [
    '系统设置',
    $this->title,
];

/**
 * @param $form SimpleActiveForm
 * @param $settings
 * @param $key
 * @param $labelSuffix
 * @return \yii\widgets\ActiveField
 */
function filedStructure($form, $settings, $key, $labelSuffix = null)
{
    return $form->field($settings[$key], '[' . $key . ']value')
        ->label(Settings::getLabel($key) . $labelSuffix)
        ->textarea(['rows' => 3]);
}

$form = SimpleActiveForm::begin([
    'title' => $this->title,
    'submitOptions' => [
        'class' => 'btn btn-primary',
        'data-confirm' => '确定修改？'
    ]
]);

echo filedStructure($form, $settings, Settings::KEY_RK_OPTIONS, '<br/>(选项每行一个)');
echo filedStructure($form, $settings, Settings::KEY_CK_OPTIONS, '<br/>(选项每行一个)');
echo filedStructure($form, $settings, Settings::KEY_SL_OPTIONS, '<br/>(选项每行一个)');
echo filedStructure($form, $settings, Settings::KEY_GH_OPTIONS, '<br/>(选项每行一个)');

echo $form->renderFooterButtons();
$form->end();
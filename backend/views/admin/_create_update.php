<?php
/** @var $this \yii\web\View */
/** @var $model Admin */

use backend\widgets\SimpleAjaxForm;
use common\models\Admin;

$form = SimpleAjaxForm::begin(['header' => ($model->isNewRecord ? '创建' : '修改') . '管理员']);

echo $form->field($model, 'username');
echo $form->field($model, 'name')->label('姓名');
echo $form->field($model, 'cellphone');
if ($model->isNewRecord) {
    echo $form->field($model, 'password_hash')->passwordInput();
}
echo $form->field($model, 'admin_role')->radioList(Admin::$adminRoleData);

$form->end();

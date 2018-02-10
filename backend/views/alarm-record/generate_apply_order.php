<?php
/** @var $this \yii\web\View */
/** @var $model \backend\models\form\AlarmRecordGenerateApplyOrderForm */

use backend\widgets\SimpleActiveForm;
use common\models\Admin;
use common\models\AlarmRecord;
use common\models\ApplyOrder;
use common\models\Person;
use yii\helpers\Html;

$form = SimpleActiveForm::begin([
    'title' => '报警记录生成调度申领单',
    'renderReturn' => true,
]);

echo Html::hiddenInput('keys', $model->alarm_record_ids);
echo $form->field($model, 'apply_order_person_id')->dropDownList(Person::findAllIdName(true), [
    'prompt' => '请选择'
]);
echo $form->field($model, 'apply_order_reason')->textarea(['rows' => 4]);
echo $form->field($model, 'apply_order_pick_type')->dropDownList(ApplyOrder::$pickTypeData);

echo $form->field($model, 'solve_id')->dropDownList(Admin::findAllIdName(true), ['prompt' => '请选择']);
echo $form->field($model, 'solve_description')->textarea(['row' => 4, 'placeholder' => '不填默认为' . AlarmRecord::SOLVE_DESCRIPTION_DEFAULT]);
?>
    <div class="col-md-10 col-md-offset-1">
        <?= $this->render('../apply-order/_apply_order_detail', [
            'models' => $model->applyOrderDetails
        ]); ?>
    </div>

    <div class="col-md-10 col-md-offset-1">
        <?= $this->render('../apply-order/_apply_order_resource', [
            'models' => $model->applyOrderResources
        ]); ?>
    </div>
<?php
echo $form->renderFooterButtons();
SimpleActiveForm::end();
<?php
/** @var $this \yii\web\View */
/** @var $applyOrder ApplyOrder */

/** @var $applyOrderDetails \common\models\ApplyOrderDetail[] */

use backend\widgets\SimpleActiveForm;
use backend\widgets\SimpleSelect2;
use common\models\ApplyOrder;
use common\models\base\Enum;
use common\models\Container;
use common\models\Person;
use common\models\Resource;
use unclead\multipleinput\TabularInput;

$resourceData = Resource::findAllIdName(null, true);
$containerData = Container::findAllIdName(true, true);

$form = SimpleActiveForm::begin([
    'title' => $applyOrder->isNewRecord ? '新增' : '修改'
]);

echo $form->field($applyOrder, 'person_id')->dropDownList(Person::findAllIdName(true), [
    'prompt' => '请选择'
])->label('申请人');
echo $form->field($applyOrder, 'reason')->textarea(['rows' => 4]);
if ($applyOrder->type == Enum::APPLY_ORDER_TYPE_APPLY) {
    echo $form->field($applyOrder, 'pick_type')->dropDownList(ApplyOrder::$pickTypeData);
}
?>
    <div class="form-group">
        <div class="col-sm-7 col-sm-offset-1">
            <?= TabularInput::widget([
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
                        'type' => SimpleSelect2::className(),
                        'options' => [
                            'data' => $resourceData,
                        ],
                    ],
                    [
                        'name' => 'quantity',
                        'title' => '数量',
                        'enableError' => true,
                        'defaultValue' => 1,
                    ],
                ],
            ]); ?>
        </div>
    </div>
<?php
echo $form->renderFooterButtons();

SimpleActiveForm::end();
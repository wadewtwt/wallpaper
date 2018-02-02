<?php
/** @var $this \yii\web\View */
/** @var $applyOrder \common\models\ApplyOrder */

/** @var $applyOrderResources \common\models\ApplyOrderResource[] */

use backend\widgets\SimpleReadTagInput;
use backend\widgets\SimpleSelect2;
use common\models\Container;
use common\models\Resource;
use unclead\multipleinput\TabularInput;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$applyOrderDetails = $applyOrder->applyOrderDetails;
$resourceData = Resource::findAllIdName(null, true, ArrayHelper::getColumn($applyOrderDetails, 'resource_id'));
$containerData = Container::findAllIdName(true, true);

$form = ActiveForm::begin([
    'id' => 'over-input'
]);
?>
<div class="invoice">
    <div class="page-header">
        <h1 class="text-center"><?= $applyOrder->getTypeName() ?>单</h1>
    </div>
    <?= $this->render('_apply_order', [
        'model' => $applyOrder,
    ]) ?>
    <?= $this->render('_apply_order_detail', [
        'models' => $applyOrderDetails,
    ]) ?>

    <p class="lead">详情</p>
    <div class="form-group row">
        <div class="col-sm-12">
            <?= TabularInput::widget([
                'models' => $applyOrderResources,
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
                        'name' => 'container_id',
                        'title' => '货位',
                        'enableError' => true,
                        'type' => SimpleSelect2::className(),
                        'options' => [
                            'data' => $containerData,
                        ],
                    ],
                    [
                        'name' => 'tag_active',
                        'title' => '有源标签',
                        'enableError' => true,
                        'type' => SimpleReadTagInput::className(),
                    ],
                    [
                        'name' => 'tag_passive',
                        'title' => '无源标签',
                        'enableError' => true,
                        'type' => SimpleReadTagInput::className(),
                    ],
                    [
                        'name' => 'quantity',
                        'title' => '数量',
                        'enableError' => true,
                        'defaultValue' => 1,
                    ],
                    [
                        'name' => 'remark',
                        'title' => '备注',
                        'enableError' => true,
                    ],
                ],
            ]); ?>
        </div>
    </div>

    <div class="box-footer">
        <a href="javascript:history.go(-1)" class="btn btn-default">取消</a>
        <input type="submit" value="完成" class="btn btn-primary">
    </div>

    <?php
    ActiveForm::end();
    ?>
</div>

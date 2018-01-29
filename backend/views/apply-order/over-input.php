<?php
/** @var $this \yii\web\View */
/** @var $applyOrder \common\models\ApplyOrder */
/** @var $applyOrderResources \common\models\ApplyOrderResource[] */

use backend\widgets\SimpleSelect2;
use common\models\Container;
use common\models\Resource;
use unclead\multipleinput\TabularInput;
use yii\widgets\ActiveForm;

$resourceData = Resource::findAllIdName(null, true);
$containerData = Container::findAllIdName(true, true);

$form = ActiveForm::begin();
?>
<div class="box">
    <div class="box-body container">
        <div class="page-header">
            <h1 class="text-center"><?= $applyOrder->getTypeName() ?>单</h1>
        </div>
        <div>
            <p><strong>申请人：</strong><?= $applyOrder->person->name ?></p>
            <p><strong>申请理由：</strong><?= $applyOrder->reason ?></p>
            <p><strong>创建时间：</strong><?= date('Y-m-d H:i:s', $applyOrder->created_at) ?></p>
        </div>

        <p>汇总</p>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="text-center">序号</th>
                <th class="text-center">资源名</th>
                <th class="text-center">数量</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($applyOrder->applyOrderDetails as $key => $applyOrderDetail): ?>
                <tr class="text-center">
                    <td><?= ($key + 1) ?></td>
                    <td><?= $applyOrderDetail->resource->name ?></td>
                    <td><?= $applyOrderDetail->quantity ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <p>详情</p>
        <div class="form-group">
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
                            'name' => 'tag_passive',
                            'title' => '无源标签',
                            'enableError' => true,
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
    </div>

    <div class="box-footer container">
        <a href="javascript:history.go(-1)" class="btn btn-default">取消</a>
        <input type="submit" value="完成" class="btn btn-primary">
    </div>

    <?php
    ActiveForm::end();
    ?>
</div>
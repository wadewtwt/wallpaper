<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\ApplyOrder */
/** @var $model \common\models\ApplyOrderDetail */
/** @var $applyOrder */
/** @var $applyOrderDetails */

use yii\widgets\ActiveForm;
use common\models\Container;

$form = ActiveForm::begin();
?>
    <div class="container">
        <div class="page-header">
            <h1 class="text-center"><?= $applyOrder->getTypeName() ?>单</h1>
        </div>
        <div>
            <p><strong>退还人：</strong><?= $applyOrder->person->name ?></p>
            <p><strong>退还理由：</strong><?= $applyOrder->reason ?></p>
            <p><strong>创建时间：</strong><?= date('Y-m-d H:i:s', $applyOrder->created_at) ?></p>
        </div>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="text-center">序号</th>
                <th class="text-center">资源名</th>
                <th class="text-center">货位名</th>
                <th class="text-center">RFID</th>
                <th class="text-center">数量</th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($applyOrderDetails as $key => $applyOrderDetail): ?>
                <tr class="text-center">
                    <td><?= ($key + 1) ?></td>
                    <td><?= $applyOrderDetail->resource->name ?></td>
                    <td><?= $form->field($applyOrderDetail->container,"[{$key}]id")->dropDownList(Container::findAllIdName(true),[])->label('')?></td>
                    <td><?php echo $form->field($applyOrderDetail, "[{$key}]rfid")->label('')->textInput(['value'=>'']) ?></td>
                    <td><?php echo $form->field($applyOrderDetail, "[{$key}]quantity")->label('') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="container">
        <a href="javascript:history.go(-1)" class="btn btn-default">取消</a>
        <input type="submit" value="完成" class="btn btn-primary">
    </div>

<?php
ActiveForm::end();

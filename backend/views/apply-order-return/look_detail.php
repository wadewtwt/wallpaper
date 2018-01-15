<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\ApplyOrder */
/** @var $model \common\models\ApplyOrderDetail */
/** @var $applyOrder */
/** @var $applyOrderDetails */

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
                    <td><?= $applyOrderDetail->container->name ?></td>
                    <td><?= $applyOrderDetail->rfid ?></td>
                    <td><?= $applyOrderDetail->quantity ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="container">
        <a href="javascript:history.go(-1)" class="btn btn-default">返回</a>
    </div>



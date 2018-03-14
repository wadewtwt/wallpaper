<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\ApplyOrderReturn */

?>
<div class="invoice">

        <div class="page-header">
            <h1 class="text-center">退还单</h1>
        </div>
        <?= $this->render('../apply-order/_apply_order', [
            'model' => $model,
        ]) ?>
        <?= $this->render('../apply-order/_apply_order_detail', [
            'models' => $model->applyOrderDetails,
            'showReal' => true,
            'showReturn' => true,
        ]) ?>

        <p class="lead">详情</p>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="text-center">序号</th>
                <th class="text-center">资源名</th>
                <th class="text-center">货位</th>
                <th class="text-center">有源标签</th>
                <th class="text-center">无源标签</th>
                <th class="text-center">数量</th>
                <th class="text-center">备注</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model->applyOrderReturnResources as $key => $applyOrderResource): ?>
                <tr class="text-center">
                    <td><?= ($key + 1) ?></td>
                    <td><?= $applyOrderResource->resource->name ?></td>
                    <td><?= $applyOrderResource->container->name ?></td>
                    <td><?= $applyOrderResource->tag_active ?></td>
                    <td><?= $applyOrderResource->tag_passive ?></td>
                    <td><?= $applyOrderResource->quantity ?></td>
                    <td><?= $applyOrderResource->remark ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="box-footer">
            <a href="javascript:history.go(-1)" class="btn btn-default">返回</a>
        </div>
</div>

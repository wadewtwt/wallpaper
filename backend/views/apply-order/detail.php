<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\ApplyOrder */
/** @var $isPrint boolean */

$isPrint = isset($isPrint) ? $isPrint : false;
?>
<div class="invoice">
    <div class="container">
        <div class="page-header">
            <h1 class="text-center"><?= $model->getTypeName() ?>单</h1>
        </div>
        <?= $this->render('_apply_order', [
            'model' => $model,
            'isPrint' => $isPrint,
        ]) ?>
        <?= $this->render('_apply_order_detail', [
            'models' => $model->applyOrderDetails,
        ]) ?>

        <?php if (!$isPrint): ?>
            <p class="lead">详情</p>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="text-center">序号</th>
                    <th class="text-center">资源名</th>
                    <th class="text-center">货位</th>
                    <th class="text-center">无源标签</th>
                    <th class="text-center">数量</th>
                    <th class="text-center">备注</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($model->applyOrderResources as $key => $applyOrderResource): ?>
                    <tr class="text-center">
                        <td><?= ($key + 1) ?></td>
                        <td><?= $applyOrderResource->resource->name ?></td>
                        <td><?= $applyOrderResource->container->name ?></td>
                        <td><?= $applyOrderResource->tag_passive ?></td>
                        <td><?= $applyOrderResource->quantity ?></td>
                        <td><?= $applyOrderResource->remark ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <?php if (!$isPrint): ?>
            <div class="box-footer container">
                <a href="javascript:history.go(-1)" class="btn btn-default">返回</a>
            </div>
        <?php endif; ?>
    </div>
</div>

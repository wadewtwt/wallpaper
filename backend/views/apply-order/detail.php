<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\ApplyOrder */
/** @var $isPrint boolean */

$isPrint = isset($isPrint) ? $isPrint : false;
?>
<div class="box">
    <div class="box-body container">
        <div class="page-header">
            <h1 class="text-center"><?= $model->getTypeName() ?>单</h1>
        </div>
        <div>
            <p><strong>申请人：</strong><?= $model->person->name ?></p>
            <p><strong>申请理由：</strong><?= $model->reason ?></p>
            <p><strong>创建时间：</strong><?= date('Y-m-d H:i:s', $model->created_at) ?></p>
            <?php if ($isPrint): ?>
                <p><strong>打印时间：</strong><?= date('Y-m-d H:i:s') ?></p>
            <?php endif; ?>
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
            <?php foreach ($model->applyOrderDetails as $key => $applyOrderDetail): ?>
                <tr class="text-center">
                    <td><?= ($key + 1) ?></td>
                    <td><?= $applyOrderDetail->resource->name ?></td>
                    <td><?= $applyOrderDetail->quantity ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (!$isPrint): ?>
            <p>详情</p>
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

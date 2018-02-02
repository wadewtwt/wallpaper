<?php
/** @var $this \yii\web\View */
/** @var $models \common\models\ApplyOrderDetail[] */
/** @var $showReal bool */
/** @var $showReturn bool */

$showReal = isset($showReal) ? $showReal : false;
$showReturn = isset($showReturn) ? $showReturn : false;

?>
<p class="lead">汇总</p>
<table class="table table-bordered">
    <thead>
    <tr>
        <th class="text-center">序号</th>
        <th class="text-center">资源名</th>
        <th class="text-center">申请数量</th>
        <?php if ($showReal): ?>
            <th class="text-center">实际数量</th>
        <?php endif; ?>
        <?php if ($showReturn): ?>
            <th class="text-center">归还数量</th>
        <?php endif; ?>

    </tr>
    </thead>
    <tbody>
    <?php foreach ($models as $key => $model): ?>
        <tr class="text-center">
            <td><?= ($key + 1) ?></td>
            <td><?= $model->resource->name ?></td>
            <td><?= $model->quantity ?></td>
            <?php if ($showReal): ?>
                <td><?= $model->quantity_real ?></td>
            <?php endif; ?>
            <?php if ($showReturn): ?>
                <td><?= $model->quantity_return ?></td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

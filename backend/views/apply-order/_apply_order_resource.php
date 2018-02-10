<?php
/** @var $this \yii\web\View */
/** @var $models \common\models\ApplyOrderResource[] */

?>
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
    <?php foreach ($models as $key => $model): ?>
        <tr class="text-center">
            <td><?= ($key + 1) ?></td>
            <td><?= $model->resource->name ?></td>
            <td><?= $model->container->name ?></td>
            <td><?= $model->tag_active ?></td>
            <td><?= $model->tag_passive ?></td>
            <td><?= $model->quantity ?></td>
            <td><?= $model->remark ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

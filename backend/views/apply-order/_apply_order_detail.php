<?php
/** @var $this \yii\web\View */
/** @var $models \common\models\ApplyOrderDetail[] */
?>
<p class="lead">汇总</p>
<table class="table table-bordered">
    <thead>
    <tr>
        <th class="text-center">序号</th>
        <th class="text-center">资源名</th>
        <th class="text-center">数量</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($models as $key => $model): ?>
        <tr class="text-center">
            <td><?= ($key + 1) ?></td>
            <td><?= $model->resource->name ?></td>
            <td><?= $model->quantity ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

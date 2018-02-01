<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\ApplyOrder */
/** @var $isPrint bool */

$isPrint = isset($isPrint) ? $isPrint : false;
?>
<div class="well no-shadow">
    <p><strong>申请人：</strong><?= $model->person->name ?></p>
    <p><strong>申请理由：</strong><?= $model->reason ?></p>
    <p><strong>创建时间：</strong><?= date('Y-m-d H:i:s', $model->created_at) ?></p>
    <?php if ($isPrint): ?>
        <p><strong>打印时间：</strong><?= date('Y-m-d H:i:s') ?></p>
    <?php endif; ?>
</div>

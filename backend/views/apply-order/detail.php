<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\ApplyOrder */
/** @var $isPrint boolean */

$isPrint = isset($isPrint) ? $isPrint : false;
?>
<div class="invoice">

        <div class="page-header">
            <h1 class="text-center"><?= $model->getTypeName() ?>单</h1>
        </div>
        <?= $this->render('_apply_order', [
            'model' => $model,
            'isPrint' => $isPrint,
        ]) ?>
        <?= $this->render('_apply_order_detail', [
            'models' => $model->applyOrderDetails,
            'showReal' => !$isPrint
        ]) ?>

        <?php if (!$isPrint): ?>
            <?= $this->render('_apply_order_resource', [
                'models' => $model->applyOrderResources,
            ]) ?>
        <?php endif; ?>
        <?php if (!$isPrint): ?>
            <div class="box-footer">
                <a href="javascript:history.go(-1)" class="btn btn-default">返回</a>
            </div>
        <?php endif; ?>

</div>

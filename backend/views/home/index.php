<?php
/** @var $this \yii\web\View */

use common\models\Resource;

$this->title = '首页';
?>

<div class="content">
    <div class="row">
        <?= $this->render('_summary_header') ?>
    </div>
    <div class="row">
        <div class="col-lg-6 col-xs-6">
            <?= $this->render('_resource_detail_operation', [
                'title' => '消耗品操作',
                'resourceType' => Resource::TYPE_EXPENDABLE,
            ]) ?>
        </div>
        <div class="col-lg-6 col-xs-6">
            <?= $this->render('_resource_detail_operation', [
                'title' => '设备操作',
                'resourceType' => Resource::TYPE_DEVICE,
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <?= $this->render('_two_icon') ?>
        </div>
        <div class="col-lg-9 col-xs-6">
            <?= $this->render('_apply_order') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-xs-6">
            <?= $this->render('_alarm') ?>
        </div>
    </div>

</div>











<?php
/** @var $this \yii\web\View */

$this->title = '首页';
$btnOptions = ['class' => 'btn btn-primary btn-sm'];
?>

<div class="content">
    <div class="row">
        <?= $this->render('_summary_header') ?>
    </div>
    <div class="row">
        <?= $this->render('_temperature',[
            'btnOptions' => $btnOptions
        ]) ?>
    </div>
    <div class="row">
        <?= $this->render('_fourCount',[
            'btnOptions' => $btnOptions
        ]) ?>
    </div>
    <div class="row">
        <div class="col-lg-12 col-xs-6">
            <?= $this->render('_camera',[
                'btnOptions' => $btnOptions
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-xs-6">
            <?= $this->render('_resource_maintenance_controll', [
                'title' => '设备消耗品<strong>临近维护时间</strong>监控',
                'btnOptions' => $btnOptions
            ]) ?>
        </div>
        <div class="col-lg-6 col-xs-6">
            <?= $this->render('_resource_scrap_controll',[
                'title' => '设备消耗品<strong>临近报废时间</strong>监控',
                'btnOptions' => $btnOptions
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-xs-6">
            <?= $this->render('_apply_order',[
                'btnOptions' => $btnOptions
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-xs-6">
            <?= $this->render('_alarm',[
                'btnOptions' => $btnOptions
            ]) ?>
        </div>
    </div>
</div>











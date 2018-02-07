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
        <?= $this->render('_temperature') ?>
        <?= $this->render('_camera') ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $this->render('_alarm', [
                'btnOptions' => $btnOptions
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $this->render('_resource_time_arrive', [
                'type' => 'maintenance'
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $this->render('_resource_time_arrive', [
                'type' => 'scrap'
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $this->render('_apply_order', [
                'btnOptions' => $btnOptions
            ]) ?>
        </div>
    </div>
</div>











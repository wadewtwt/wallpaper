<?php
/** @var $this \yii\web\View */

$this->title = '首页';
?>

<div class="content">
    <div class="row">
        <?= $this->render('_summary_header') ?>
    </div>
    <div class="row">
        <div class="col-lg-6 col-xs-6">
            <?= $this->render('_expendable_detail') ?>
        </div>
        <div class="col-lg-6 col-xs-6">
            <?= $this->render('_device_detail') ?>
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











<?php
/** @var $this \yii\web\View */

$this->title = '首页';
?>

<div class="content">
    <div class="row">

    </div>
    <div class="row">
        <?= $this->render('_tag') ?>
        <?= $this->render('_temperature') ?>
    </div>

<!--    <div class="row">-->
<!--        <div class="col-md-12">-->
<!--            --><?//= $this->render('_alarm') ?>
<!--        </div>-->
<!--    </div>-->
<!--    <div class="row">-->
<!--        <div class="col-md-6">-->
<!--            --><?//= $this->render('_resource_time_arrive', [
//                'type' => 'maintenance'
//            ]) ?>
<!--        </div>-->
<!--        <div class="col-md-6">-->
<!--            --><?//= $this->render('_resource_time_arrive', [
//                'type' => 'scrap'
//            ]) ?>
<!--        </div>-->
<!--    </div>-->
<!--    <div class="row">-->
<!--        <div class="col-md-12">-->
<!--            --><?//= $this->render('_apply_order') ?>
<!--        </div>-->
<!--    </div>-->
</div>











<?php

use common\models\ApplyOrder;
use common\models\base\Enum;
use common\models\Container;
use common\models\Person;
use yii\helpers\Url;

$countAllPerson = Person::find()->where(['status' => Person::STATUS_NORMAL])->count(); // 计算符合标准的总人数
$countAllContainer = Container::find()->count(); // 计算总货架数(无状态判断)
$countInput = ApplyOrder::summaryNearCount(7, Enum::APPLY_ORDER_TYPE_INPUT, ApplyOrder::STATUS_OVER); // 入库数量
$countOutput = ApplyOrder::summaryNearCount(7, Enum::APPLY_ORDER_TYPE_OUTPUT, ApplyOrder::STATUS_OVER); // 出库数量
?>

<div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua">
        <div class="inner">
            <h3><?= $countAllPerson ?></h3>

            <p>人员管理</p>
        </div>
        <div class="icon">
            <i class="glyphicon glyphicon-user" style="font-size: 75px;"></i>
        </div>
        <a href="<?= Url::to(['/person']) ?>" class="small-box-footer">更多 <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>

<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-green">
        <div class="inner">
            <h3><?= $countAllContainer ?></h3>
            <p>货区管理</p>
        </div>
        <div class="icon">
            <i class="glyphicon glyphicon-tasks" style="font-size: 75px;"></i>
        </div>
        <a href="<?= Url::to('/container') ?>" class="small-box-footer">更多 <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>

<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-yellow">
        <div class="inner">
            <!--真正入了库的-->
            <h3><?= $countInput ?></h3>
            <p>入库数量(过去一周)</p>
        </div>
        <div class="icon">
            <i class="glyphicon glyphicon-import" style="font-size: 75px;"></i>
        </div>


        <a href="<?= Url::to('/apply-order-input') ?>" class="small-box-footer">更多 <i
                    class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
<!-- ./col -->
<div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-red">
        <div class="inner">
            <h3><?= $countOutput ?></h3>
            <p>出库、申领数量（过去一周）</p>
        </div>
        <div class="icon">
            <i class="glyphicon glyphicon-export" style="font-size: 75px;"></i>
        </div>
        <a href="<?= Url::to('/apply-order-output') ?>" class="small-box-footer">更多 <i
                    class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>

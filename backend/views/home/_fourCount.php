<?php
/** 设备和消耗品的四个统计 */

use yii\helpers\Url;
use common\models\Resource;

$countDevice = Resource::find()->where(['type' => Resource::TYPE_DEVICE])->sum('current_stock');
$countDeviceTypes = Resource::find()->where(['type' => Resource::TYPE_DEVICE])->count();

$countExpendable = Resource::find()->where(['type' => Resource::TYPE_EXPENDABLE])->sum('current_stock');
$countExpendableTypes = Resource::find()->where(['type' => Resource::TYPE_EXPENDABLE])->count();
?>
<div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-primary">
        <div class="inner">
            <h3><?= $countDeviceTypes ?></h3>

            <p>设备种类数量</p>
        </div>
        <div class="icon">
            <i class="glyphicon glyphicon-th-list" style="font-size: 75px;"></i>
        </div>
        <a href="<?= Url::to(['/device']) ?>" class="small-box-footer">更多 <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>

<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-primary">
        <div class="inner">
            <h3><?= $countDevice ?></h3>
            <p>设备总数量</p>
        </div>
        <div class="icon">
            <i class="glyphicon glyphicon-th" style="font-size: 75px;"></i>
        </div>
        <a href="<?= Url::to('/device') ?>" class="small-box-footer">更多 <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>

<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-red">
        <div class="inner">
            <!--真正入了库的-->
            <h3><?= $countExpendableTypes ?></h3>
            <p>消耗品种类数量</p>
        </div>
        <div class="icon">
            <i class="glyphicon glyphicon-th-list" style="font-size: 75px;"></i>
        </div>


        <a href="<?= Url::to('/expendable') ?>" class="small-box-footer">更多 <i
                class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
<!-- ./col -->
<div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-red">
        <div class="inner">
            <h3><?= $countExpendable ?></h3>
            <p>消耗品总数量</p>
        </div>
        <div class="icon">
            <i class="glyphicon glyphicon-th" style="font-size: 75px;"></i>
        </div>
        <a href="<?= Url::to('/expendable') ?>" class="small-box-footer">更多 <i
                class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
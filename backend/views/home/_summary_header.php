<?php
/** @var $this \yii\web\View */

use common\models\ApplyOrder;
use common\models\base\Enum;
use common\models\Container;
use common\models\Person;
use common\models\Resource;
use yii\helpers\Html;

$data = [
    [
        'title' => '人员管理',
        'count' => Person::find()->where(['status' => Person::STATUS_NORMAL])->count(),
        'icon' => 'user',
        'bg' => 'aqua',
        'more' => ['/person'],
    ],
    [
        'title' => '货位总数',
        'count' => Container::find()->sum('total_quantity'),
        'icon' => 'database',
        'bg' => 'green',
        'more' => ['/container'],
    ],
    [
        'title' => '出库量（最近一周）',
        'count' => ApplyOrder::summaryNearCount(7, Enum::APPLY_ORDER_TYPE_INPUT, ApplyOrder::STATUS_OVER),
        'icon' => 'level-up',
        'bg' => 'red',
        'more' => ['/apply-order-output'],
    ],
    [
        'title' => '入库量（最近一周）',
        'count' => ApplyOrder::summaryNearCount(7, Enum::APPLY_ORDER_TYPE_OUTPUT, ApplyOrder::STATUS_OVER),
        'icon' => 'level-down',
        'bg' => 'red',
        'more' => ['/apply-order-input'],
    ],
    [
        'title' => '当前总装备库存',
        'count' => Resource::find()->where(['type' => Resource::TYPE_DEVICE])->sum('current_stock'),
        'icon' => 'th-large',
        'bg' => 'teal',
        'more' => ['/device'],
    ],
    [
        'title' => '总装备类别量',
        'count' => Resource::find()->where(['type' => Resource::TYPE_DEVICE])->count(),
        'icon' => 'th-large',
        'bg' => 'teal',
        'more' => ['/device'],
    ],
    [
        'title' => '当前总消耗品库存',
        'count' => Resource::find()->where(['type' => Resource::TYPE_EXPENDABLE])->sum('current_stock'),
        'icon' => 'th',
        'bg' => 'purple',
        'more' => ['/expendable'],
    ],
    [
        'title' => '总消耗品类别量',
        'count' => Resource::find()->where(['type' => Resource::TYPE_EXPENDABLE])->count(),
        'icon' => 'th',
        'bg' => 'purple',
        'more' => ['/expendable'],
    ],
];
?>
<?php foreach ($data as $item): ?>
    <div class="col-md-3">
        <div class="small-box bg-<?= $item['bg'] ?>">
            <div class="inner">
                <h3><?= $item['count'] ?></h3>
                <p><?= $item['title'] ?></p>
            </div>
            <div class="icon">
                <i class="fa fa-<?= $item['icon'] ?>" style="font-size: 75px;"></i>
            </div>
            <?= Html::a('更多 <i class="fa arrow-circle-right"></i>', $item['more'], ['class' => 'small-box-footer']) ?>
        </div>
    </div>
<?php endforeach; ?>

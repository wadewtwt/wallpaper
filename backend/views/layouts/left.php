<?php
/* @var $this \yii\web\View */

use common\models\Admin;
use kriss\modules\auth\tools\AuthValidate;
use common\models\base\Auth;

/** @var $admin Admin */
$admin = Yii::$app->user->identity;

$menuTitle = '总管理后台';
$baseUrl = '';
$menu = [
    ['label' => '首页', 'icon' => 'circle-o', 'url' => [$baseUrl . '/home']],
    [
        'label' => '人员管理', 'icon' => 'list', 'url' => '#',
        'items' => [
            ['label' => '职称管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/position']],
            ['label' => '人员管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/person']],
        ]
    ],
    [
        'label' => '图片管理', 'icon' => 'list', 'url' => '#',
        'items' => [
            ['label' => '类型管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/kinds']],
            ['label' => '壁纸管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/paper']],
        ]
    ],

    ['label' => '管理员管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/admin']],

];
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel text-center">
            <h4><?= $menuTitle ?></h4>
        </div>

        <?= dmstr\widgets\Menu::widget([
            'items' => array_merge([
                ['label' => '菜单', 'options' => ['class' => 'header']],
            ], $menu),
        ]) ?>

    </section>

</aside>
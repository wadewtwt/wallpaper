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
            ['label' => '人员管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/person']],
            ['label' => '职称管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/position']],
        ]
    ],
    [
        'label' => '消耗品管理', 'icon' => 'list', 'url' => '#',
        'items' => [
            ['label' => '消耗品列表', 'icon' => 'circle-o', 'url' => [$baseUrl . '/res-expendable']],
            ['label' => '消耗品详细', 'icon' => 'circle-o', 'url' => [$baseUrl . '/expendable-detail']],
        ]
    ],
    [
        'label' => '设备管理', 'icon' => 'list', 'url' => '#',
        'items' => [
            ['label' => '设备列表', 'icon' => 'circle-o', 'url' => [$baseUrl . '/res-device']],
            ['label' => '设备详细', 'icon' => 'circle-o', 'url' => [$baseUrl . '/device']],
            ['label' => '设备使用明细', 'icon' => 'circle-o', 'url' => [$baseUrl . '/device-detail']],
        ]
    ],

    ['label' => '货区管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/container']],
    ['label' => '入库管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/apply-order-input']],
    ['label' => '出库管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/apply-order-output']],
    ['label' => '申领管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/apply-order-apply']],
    ['label' => '退还管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/apply-order-return']],
    ['label' => '用户管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/user']],
    ['label' => '管理员管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/admin']],
    [
        'label' => '权限管理', 'icon' => 'list', 'url' => '#', 'visible' => AuthValidate::has([Auth::PERMISSION_VIEW, Auth::ROLE_VIEW]),
        'items' => [
            ['label' => '权限查看', 'icon' => 'circle-o', 'url' => [$baseUrl . '/auth/permission'], 'visible' => AuthValidate::has([Auth::PERMISSION_VIEW])],
            ['label' => '角色管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/auth/role'], 'visible' => AuthValidate::has([Auth::ROLE_VIEW])],
        ]
    ]
];
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel text-center">
            <h4><?= $menuTitle ?></h4>
        </div>

        <?= dmstr\widgets\Menu::widget([
            'options' => [
                'class' => 'sidebar-menu',
                'data-widget' => 'tree'
            ],
            'items' => array_merge([
                ['label' => '菜单', 'options' => ['class' => 'header']],
            ], $menu),
        ]) ?>

    </section>

</aside>
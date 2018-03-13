<?php
/* @var $this \yii\web\View */

use common\models\Admin;
use kriss\modules\auth\tools\AuthValidate;
use common\models\base\Auth;

/** @var $admin Admin */
$admin = Yii::$app->user->identity;
$isSuperAdmin = $admin->admin_role == Admin::ADMIN_ROLE_SUPER_ADMIN;

$menuTitle = '总管理后台';
$baseUrl = '';
$menu = [
    ['label' => '首页', 'icon' => 'circle-o', 'url' => [$baseUrl . '/home'], 'visible' => $isSuperAdmin],
    [
        'label' => '人员管理', 'icon' => 'list', 'url' => '#', 'visible' => $isSuperAdmin,
        'items' => [
            ['label' => '职称管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/position']],
            ['label' => '人员管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/person']],
        ]
    ],
    [
        'label' => '仓库货位管理', 'icon' => 'list', 'url' => '#', 'visible' => $isSuperAdmin,
        'items' => [
            ['label' => '仓库管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/store']],
            ['label' => '货区管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/container']],
        ]
    ],
    ['label' => '资源分类管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/resource-type'], 'visible' => $isSuperAdmin,],
    [
        'label' => '装备管理', 'icon' => 'list', 'url' => '#', 'visible' => $isSuperAdmin,
        'items' => [
            ['label' => '装备列表', 'icon' => 'circle-o', 'url' => [$baseUrl . '/device']],
            ['label' => '装备明细', 'icon' => 'circle-o', 'url' => [$baseUrl . '/device-detail']],
            ['label' => '装备操作记录', 'icon' => 'circle-o', 'url' => [$baseUrl . '/device-detail-operation']],
        ]
    ],
    [
        'label' => '消耗品管理', 'icon' => 'list', 'url' => '#', 'visible' => $isSuperAdmin,
        'items' => [
            ['label' => '消耗品列表', 'icon' => 'circle-o', 'url' => [$baseUrl . '/expendable']],
            ['label' => '消耗品明细', 'icon' => 'circle-o', 'url' => [$baseUrl . '/expendable-detail']],
            ['label' => '消耗品操作记录', 'icon' => 'circle-o', 'url' => [$baseUrl . '/expendable-detail-operation']],
        ]
    ],
    [
        'label' => '出入库管理', 'icon' => 'list', 'url' => '#',
        'items' => [
            ['label' => '入库管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/apply-order-input']],
            ['label' => '出库管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/apply-order-output']],
        ]
    ],
    [
        'label' => '调度管理', 'icon' => 'list', 'url' => '#',
        'items' => [
            ['label' => '申领管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/apply-order-apply']],
            ['label' => '退还管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/apply-order-return']],
        ]
    ],
    [
        'label' => '监控设备管理', 'icon' => 'list', 'url' => '#', 'visible' => $isSuperAdmin,
        'items' => [
            ['label' => '温湿度设备管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/temperature']],
            ['label' => '摄像头设备管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/camera']],
            ['label' => '联动设置', 'icon' => 'circle-o', 'url' => [$baseUrl . '/alarm-config']],
        ]
    ],
    ['label' => '报警记录管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/alarm-record']],
    ['label' => '管理员管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/admin'], 'visible' => $isSuperAdmin,],
    ['label' => '操作日志管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/operate-log'], 'visible' => $isSuperAdmin,],
    [
        'label' => '权限管理', 'icon' => 'list', 'url' => '#', 'visible' => AuthValidate::has([Auth::PERMISSION_VIEW, Auth::ROLE_VIEW]),
        'items' => [
            ['label' => '权限查看', 'icon' => 'circle-o', 'url' => [$baseUrl . '/auth/permission'], 'visible' => AuthValidate::has([Auth::PERMISSION_VIEW])],
            ['label' => '角色管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/auth/role'], 'visible' => AuthValidate::has([Auth::ROLE_VIEW])],
        ]
    ],
    ['label' => '系统设置', 'icon' => 'circle-o', 'url' => [$baseUrl . '/settings'], 'visible' => $isSuperAdmin,],
];

$menuHelper = new \backend\components\MenuHelper([
    'cacheEnable' => YII_DEBUG ? false : true,
    'cacheTime' => 60,
    'cacheKey' => [$admin->admin_role]
]);
$menu = $menuHelper->changeActive($menu);
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
<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */
/** @var $searchModel backend\models\ResDeviceSearch */

use backend\widgets\SimpleDynaGrid;
use yii\helpers\Html;

$this->title = '设备列表';
$this->params['breadcrumbs'] = [
    '设备',
    $this->title,
];

echo $this->render('_search', [
    'model' => $searchModel,
]);

$columns = [
    [
        'attribute' => 'name',
    ],
    [
        'attribute' => 'min_stock',
    ],
    [
        'attribute' => 'current_stock',
    ],
    [
        'attribute' => 'scrap_cycle',
    ],
    [
        'attribute' => 'maintenance_cycle',
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '300px',
        'template' => '{update} {delete} {detail}',
        'buttons' => [
            'update' => function ($url) {
                $options = [
                    'class' => 'btn btn-default show_ajax_modal',
                ];
                return Html::a('更新', $url, $options);
            },
            'delete' => function ($url) {
                $options = [
                    'class' => 'btn btn-danger',
                    'data-method' => 'post',
                    'data-confirm' => '确定删除该设备？'
                ];
                return Html::a('删除', $url, $options);
            },
            'detail' => function ($url, $model) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                return Html::a('物品明细', ['/device', 'DeviceSearch[resource_id]' => $model->id], $options);
            },
        ],
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-res-device-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    'extraToolbar' => [
        [
            'content' => Html::a('新增', ['create'], ['class' => 'btn btn-default show_ajax_modal'])
        ]
    ]
]);
$simpleDynaGrid->renderDynaGrid();

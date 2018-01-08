<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */

/** @var $searchModel backend\models\DeviceSearch */

use backend\widgets\SimpleDynaGrid;
use common\models\Device;
use yii\helpers\Html;

$this->title = '设备详细列表';
$this->params['breadcrumbs'] = [
    '设备管理',
    $this->title,
];

echo $this->render('_search', [
    'model' => $searchModel,
]);

$columns = [
    [
        'attribute' => 'resource.name',
    ],
    [
        'attribute' => 'container.name',
    ],
    [
        'attribute' => 'is_online',
        'value' => function (Device $model) {
            return $model->is_online ? '在线' : '离线';
        }
    ],
    [
        'attribute' => 'online_change_at',
        'format' => ['date', 'php:Y-m-d']
    ],
    [
        'attribute' => 'maintenance_at',
        'format' => ['date', 'php:Y-m-d H:i:s']
    ],
    [
        'attribute' => 'scrap_at',
        'format' => ['date', 'php:Y-m-d H:i:s']
    ],
    [
        'attribute' => 'quantity',
    ],
    [
        'attribute' => 'status',
        'value' => function (Device $model) {
            return $model->getStatusName();
        }
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '150px',
        'template' => '{detail}',
        'buttons' => [
            'detail' => function ($url,$model) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                return Html::a('使用明细', ['/device-detail','device_id' => $model->id], $options);
            },
        ],
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-device-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
]);
$simpleDynaGrid->renderDynaGrid();

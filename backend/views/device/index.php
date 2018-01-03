<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */
/** @var $searchModel backend\models\DeviceSearch */

use backend\widgets\SimpleDynaGrid;
use common\models\Device;

$this->title = '设备明细列表';
$this->params['breadcrumbs'] = [
    '设备明细',
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
        'value' => function (Device $model){
            return $model->getDataIsOnline();
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
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-device-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
]);
$simpleDynaGrid->renderDynaGrid();

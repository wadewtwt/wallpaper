<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */

use backend\widgets\SimpleDynaGrid;
use common\models\DeviceDetail;

$this->title = '设备使用明细列表';
$this->params['breadcrumbs'] = [
    '设备使用明细',
    $this->title,
];

$columns = [
    [
        'attribute' => 'device.resource_id',
    ],
    [
        'attribute' => 'operation',
        'value' => function (DeviceDetail $model){
            return $model->getDeviceDetailOperation();
        }
    ],
    [
        'attribute' => 'remark',
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-device-detail-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
]);
$simpleDynaGrid->renderDynaGrid();

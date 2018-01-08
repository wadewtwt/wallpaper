<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */
/** @var $searchModel backend\models\ApplyOrderDetailSearch */

use backend\widgets\SimpleDynaGrid;

$this->title = '申请单详情表列表';
$this->params['breadcrumbs'] = [
    '申请单详情表',
    $this->title,
];

echo $this->render('_search', [
    'model' => $searchModel,
]);

$columns = [
    [
        'attribute' => 'apply_order_id',
    ],
    [
        'attribute' => 'resource_id',
    ],
    [
        'attribute' => 'container_id',
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-apply-order-detail-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
]);
$simpleDynaGrid->renderDynaGrid();

<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */

/** @var $searchModel \backend\models\ResourceDetailOperationSearch */

use backend\widgets\SimpleDynaGrid;
use common\models\ResourceDetailOperation;

$typeName = $searchModel->getTypeName();

$this->title = $typeName . '操作记录列表';
$this->params['breadcrumbs'] = [
    $typeName . '管理',
    $this->title,
];

$columns = [
    [
        'attribute' => 'resourceDetail.resource.name',
    ],
    [
        'attribute' => 'applyOrder.person.name',
        'label' => '申请人'
    ],
    [
        'attribute' => 'operation',
        'value' => function (ResourceDetailOperation $model) {
            return $model->getOperationName();
        }
    ],
    [
        'attribute' => 'created_at',
        'label' => '操作时间',
        'format' => ['datetime', 'php:Y-m-d H:i:s'],
    ],
    [
        'attribute' => 'remark',
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-resource-detail-operation-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
]);
$simpleDynaGrid->renderDynaGrid();

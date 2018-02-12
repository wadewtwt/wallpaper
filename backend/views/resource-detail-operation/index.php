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

echo $this->render('_search', [
    'model' => $searchModel,
]);

$columns = [
    [
        'width' => '150px',
        'attribute' => 'resourceDetail.resource.name',
    ],
    [
        'width' => '150px',
        'attribute' => 'applyOrder.person.name',
        'label' => '申请人'
    ],
    [
        'width' => '150px',
        'attribute' => 'operation',
        'value' => function (ResourceDetailOperation $model) {
            return $model->getOperationName();
        }
    ],
    [
        'width' => '200px',
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

<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */
/** @var $searchModel backend\models\ExpendableDetailSearch */

use backend\widgets\SimpleDynaGrid;
use common\models\ExpendableDetail;

$this->title = '消耗品明细列表';
$this->params['breadcrumbs'] = [
    '消耗品明细',
    $this->title,
];

echo $this->render('_search', [
    'model' => $searchModel,
]);

$columns = [
    [
        'attribute' => 'resource.name'
    ],
    [
        'attribute' => 'container.name',
    ],
    [
        'attribute' => 'operation',
        'value' => function (ExpendableDetail $model){
            return $model->getOperationName();
        }
    ],
    [
        'attribute' => 'created_at',
        'label' => '出入库时间',
        'format' => ['datetime','php:Y-m-d']
    ],
    [
        'attribute' => 'quantity',
    ],
    [
        'attribute' => 'scrap_at',
        'format' => ['datetime', 'php:Y-m-d H:i:s']
    ],
    [
        'attribute' => 'remark',
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-expendable-detail-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    'extraToolbar' => [
        [

        ]
    ]
]);
$simpleDynaGrid->renderDynaGrid();

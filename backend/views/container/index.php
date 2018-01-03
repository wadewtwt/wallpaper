<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */
/** @var $searchModel backend\models\ContainerSearch */

use backend\widgets\SimpleDynaGrid;
use yii\helpers\Html;

$this->title = '货位管理列表';
$this->params['breadcrumbs'] = [
    '货位管理',
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
        'attribute' => 'total_quantity',
    ],
    [
        'attribute' => 'free_quantity',
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '150px',
        'template' => '{detail}',
        'buttons' => [
            'detail' => function ($url) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                return Html::a('查看货物', $url, $options);
            },
        ],
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-container-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
]);
$simpleDynaGrid->renderDynaGrid();

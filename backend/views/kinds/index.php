<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */
/** @var $searchModel backend\models\KindsSearch */

use backend\widgets\SimpleDynaGrid;
use yii\helpers\Html;

$this->title = '类型管理列表';
$this->params['breadcrumbs'] = [
    '类型管理',
    $this->title,
];

echo $this->render('_search', [
    'model' => $searchModel,
]);

$columns = [
    [
        'attribute' => 'id',
    ],
    [
        'attribute' => 'title',
    ],
    [
        'attribute' => 'type',
    ],
    [
        'attribute' => 'pid',
    ],
    [
        'attribute' => 'created_at',
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '150px',
        'template' => '{update} {delete}',
        'buttons' => [
            'update' => function ($url) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                return Html::a('更新', $url, $options);
            },
            'delete' => function ($url) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                return Html::a('删除', $url, $options);
            },
        ],
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-kinds-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    'extraToolbar' => [
        [
            'content' => Html::a('新增', ['create'], ['class' => 'btn btn-default show_ajax_modal'])
        ]
    ]
]);
$simpleDynaGrid->renderDynaGrid();

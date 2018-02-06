<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */

use backend\widgets\SimpleDynaGrid;
use yii\helpers\Html;

$this->title = '资源分类管理列表';
$this->params['breadcrumbs'] = [
    '资源分类管理',
    $this->title,
];

$columns = [
    [
        'attribute' => 'id',
    ],
    [
        'attribute' => 'name',
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '150px',
        'template' => '{update} {delete}',
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
                    'data-confirm' => '确定删除该资源分类？'
                ];
                return Html::a('删除', $url, $options);
            },
        ],
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-resource-type-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    'extraToolbar' => [
        [
            'content' => Html::a('新增', ['create'], ['class' => 'btn btn-default show_ajax_modal'])
        ]
    ]
]);
$simpleDynaGrid->renderDynaGrid();

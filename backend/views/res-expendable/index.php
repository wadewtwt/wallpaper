<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */
/** @var $searchModel backend\models\ResExpendableSearch */

use backend\widgets\SimpleDynaGrid;
use yii\helpers\Html;

$this->title = '消耗品列表';
$this->params['breadcrumbs'] = [
    '消耗品',
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
        'attribute' => 'min_stock',
    ],
    [
        'attribute' => 'current_stock',
    ],
    [
        'attribute' => 'scrap_cycle',
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '250px',
        'template' => '{update} {delete} {expendable-detail}',
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
                    'data-confirm' => '确定删除该消耗品？'
                ];
                return Html::a('删除', $url, $options);
            },
            'expendable-detail' => function($url,$model){
                $option = [
                    'class' => 'btn btn-default'
                ];
                return Html::a('物品明细', ['/expendable-detail','resource_id'=>$model->id], $option);
            }
        ],
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-resource-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    'extraToolbar' => [
        [
            'content' => Html::a('新增', ['create'], ['class' => 'btn btn-default show_ajax_modal'])
        ]
    ]
]);
$simpleDynaGrid->renderDynaGrid();

<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */
/** @var $searchModel backend\models\PaperSearch */

use backend\widgets\SimpleDynaGrid;
use yii\helpers\Html;

$this->title = '图片管理列表';
$this->params['breadcrumbs'] = [
    '图片管理',
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
        'attribute' => 'url',
        'label' => '这图',
        'value' => function($model){
            return Html::img($model->url,[
                'alt' => '图',
                'width' =>'60px'
            ]);
        },
        'format' => 'html'
    ],
//    [
//        'attribute' => 'type',
//    ],
    [
        'attribute' => 'kind',
        'label' => '类型',
        'value' => 'kinds.title',
        'width' => '100px'
    ],
    [
        'attribute' => 'introduction',
    ],
    [
        'attribute' => 'praise',
    ],
    [
        'attribute' => 'view',
    ],
    [
        'attribute' => 'created_at',
        'format' => ['date','php:Y-m-d H:i:s']
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
                    'data-confirm' => '您确定不显示么？'
                ];
                return Html::a('不显示', $url, $options);
            },
        ],
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-paper-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    'extraToolbar' => [
        [
            'content' => Html::a('新增', ['create'], ['class' => 'btn btn-default show_ajax_modal'])
        ]
    ]
]);
$simpleDynaGrid->renderDynaGrid();

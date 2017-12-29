<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */

/** @var $searchModel backend\models\PersonSearch */

use backend\widgets\SimpleDynaGrid;
use yii\helpers\Html;

$this->title = '人员管理列表';
$this->params['breadcrumbs'] = [
    '人员管理',
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
        'attribute' => 'position.name',
        'label' => '职称'
    ],
    [
        'attribute' => 'cellphone',
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '150px',
        'template' => '{update} {delete}{device-detail}',
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
                    'data-confirm' => '确定删除该人员？'
                ];
                return Html::a('删除', $url, $options);
            },
            'device-detail' => function ($url) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                return "<br /><br />".Html::a('查看装备领取记录', $url, $options);
            },
        ],
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-person-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    'extraToolbar' => [
        [
            'content' => Html::a('新增', ['create'], ['class' => 'btn btn-default show_ajax_modal'])
        ],
        [
            'content' => Html::a('查看已删除', ['delete-index'], ['class' => 'btn btn-default show_ajax_modal'])
        ]
    ]
]);
$simpleDynaGrid->renderDynaGrid();

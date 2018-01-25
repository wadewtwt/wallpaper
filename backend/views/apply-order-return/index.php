<?php
/** @var $this \yii\web\View */
/** @var $dataProvider \common\components\ActiveDataProvider */
/** @var $searchModel \backend\models\ApplyOrderSearch */

use backend\widgets\SimpleDynaGrid;
use common\models\ApplyOrder;
use yii\helpers\Html;

$this->title = '退还管理列表';
$this->params['breadcrumbs'] = [
    '退还管理',
    $this->title
];

echo $this->render('_search', [
    'model' => $searchModel
]);

$columns = [
    [
        'attribute' => 'created_at',
        'label' => '退还时间',
        'format' => ['datetime', 'php:Y-m-d H:i:s']
    ],
    [
        'attribute' => 'person.name',
        'label' => '退还人'
    ],
    [
        'attribute' => 'reason',
        'width' => '300px',
    ],
    [
        'attribute' => 'status',
        'value' => function(ApplyOrder $model){
            return $model->getReturnStatusName();
        }
    ],

    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '350px',
        'template' => '{return} {look}',
        'buttons' => [
            'return' => function ($url,$model) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                if ($model->status == ApplyOrder::STATUS_RETURN_OVER){
                    return '';
                }
                return Html::a('退还', $url, $options);
            },
            'look' => function ($url) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                return Html::a('查看', $url, $options);
            },
        ],
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-apply-order-return-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
]);
$simpleDynaGrid->renderDynaGrid();
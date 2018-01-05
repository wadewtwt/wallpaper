<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */
/** @var $searchModel backend\models\ApplyOrderSearch */

use backend\widgets\SimpleDynaGrid;
use yii\helpers\Html;
use common\models\ApplyOrder;

$this->title = '入库单管理列表';
$this->params['breadcrumbs'] = [
    '入库单管理',
    $this->title,
];

echo $this->render('_search', [
    'model' => $searchModel,
]);

$columns = [
    [
        'attribute' => 'type',
        'value' => function (ApplyOrder $model) {
            return $model->operationData();
        }
    ],
    [
        'attribute' => 'person.name',
    ],
    [
        'attribute' => 'reason',
    ],
    [
        'attribute' => 'delete_reason',
    ],
    [
        'attribute' => 'pick_type',
        'value' => function (ApplyOrder $model) {
            return $model->pickTypeData();
        }
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '250px',
        'template' => '{print} {update} {delete} {detail} {pass}',
        'buttons' => [
            'print' => function ($url) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                return Html::a('打印', $url, $options);
            },
            'update' => function ($url) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                return Html::a('修改', $url, $options);
            },
            'delete' => function ($url) {
                $options = [
                    'class' => 'btn btn-default show_ajax_modal',
                ];
                return Html::a('作废', $url, $options);
            },
            'detail' => function ($url) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                return Html::a('明细', $url, $options);
            },
            'pass' => function ($url) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                return Html::a('审核通过', $url, $options);
            },
        ],
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-apply-order-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    'extraToolbar' => [
        [
            'content' => Html::a('新增入库单', ['create'], ['class' => 'btn btn-default'])
        ]
    ]
]);
$simpleDynaGrid->renderDynaGrid();

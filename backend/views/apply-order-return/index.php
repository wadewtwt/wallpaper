<?php
/** @var $this \yii\web\View */
/** @var $dataProvider \common\components\ActiveDataProvider */
/** @var $searchModel \backend\models\ApplyOrderSearch */

use backend\widgets\SimpleDynaGrid;
use common\models\ApplyOrderReturn;
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
        'width' => '100px',
        'attribute' => 'person.name',
        'label' => '申请人'
    ],
    [
        'attribute' => 'reason',
        'width' => '300px',
    ],
    [
        'width' => '80px',
        'attribute' => 'status',
        'value' => function (ApplyOrderReturn $model) {
            return $model->getStatusName();
        }
    ],
    [
        'width' => '100px',
        'attribute' => 'created_at',
        'label' => '申请时间',
        'format' => ['datetime', 'php:Y-m-d H:i:s']
    ],
    [
        'width' => '100px',
        'attribute' => 'return_at',
        'label' => '退还时间',
        'value' => function (ApplyOrderReturn $model) {
            if ($model->status != ApplyOrderReturn::STATUS_RETURN_OVER) {
                return '';
            }
            return date('Y-m-d H:i:s', $model->return_at);
        },
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '200px',
        'template' => '{detail} {detail-return} {over}',
        'buttons' => [
            'detail' => function ($url) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                return Html::a('申领明细', $url, $options);
            },
            'detail-return' => function ($url, ApplyOrderReturn $model) {
                if ($model->status != ApplyOrderReturn::STATUS_RETURN_OVER) {
                    return '';
                }
                $options = [
                    'class' => 'btn btn-default',
                ];
                return Html::a('退还明细', $url, $options);
            },
            'over' => function ($url, $model) {
                if ($model->status == ApplyOrderReturn::STATUS_RETURN_OVER) {
                    return '';
                }
                $options = [
                    'class' => 'btn btn-success',
                ];
                return Html::a('退还', $url, $options);
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
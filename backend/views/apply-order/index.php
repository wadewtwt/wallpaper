<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */
/** @var $searchModel backend\models\ApplyOrderSearch */

use backend\widgets\SimpleDynaGrid;
use yii\helpers\Html;
use common\models\ApplyOrder;

$typeName = $searchModel->getTypeName();

$this->title = $typeName . '单管理列表';
$this->params['breadcrumbs'] = [
    $typeName . '单管理',
    $this->title,
];

echo $this->render('_search', [
    'model' => $searchModel
]);

$columns = [
    [
        'attribute' => 'created_at',
        'label' => '申请时间',
        'format' => ['datetime', 'php:Y-m-d H:i:s']
    ],
    [
        'attribute' => 'person.name',
        'label' => '申请人',
    ],
    [
        'attribute' => 'reason',
        'width' => '300px',
    ],
    [
        'attribute' => 'status',
        'value' => function (ApplyOrder $model) {
            return $model->getStatusName();
        }
    ],
    [
        'attribute' => 'delete_reason',
        'width' => '300px',
    ],
];

if (in_array($searchModel->type, [ApplyOrder::TYPE_APPLY, ApplyOrder::TYPE_RETURN])) {
    $columns = array_merge($columns, [
        [
            'attribute' => 'pick_type',
            'value' => function (ApplyOrder $model) {
                return $model->getPickTypeName();
            }
        ]
    ]);
}

$columns = array_merge($columns, [
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '350px',
        'template' => '{detail} {update} {print} {pass} {delete} {over}',
        'buttons' => [
            'detail' => function ($url) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                return Html::a('明细', $url, $options);
            },
            'print' => function ($url, ApplyOrder $model) {
                if (!$model->checkStatus(ApplyOrder::STATUS_OPERATE_PRINT)) {
                    return '';
                }
                $options = [
                    'class' => 'btn btn-default',
                    'target' => '_blank'
                ];
                return Html::a('打印', $url, $options);
            },
            'update' => function ($url, ApplyOrder $model) {
                if (!$model->checkStatus(ApplyOrder::STATUS_OPERATE_UPDATE)) {
                    return '';
                }
                $options = [
                    'class' => 'btn btn-default',
                ];
                return Html::a('修改', $url, $options);
            },
            'pass' => function ($url, ApplyOrder $model) {
                if (!$model->checkStatus(ApplyOrder::STATUS_AUDITED)) {
                    return '';
                }
                $options = [
                    'class' => 'btn btn-success',
                    'data-confirm' => '确认审核通过？'
                ];
                return Html::a('审核通过', $url, $options);
            },
            'delete' => function ($url, ApplyOrder $model) {
                if (!$model->checkStatus(ApplyOrder::STATUS_DELETE)) {
                    return '';
                }
                $options = [
                    'class' => 'btn btn-danger show_ajax_modal',
                ];
                return Html::a('作废', $url, $options);
            },
            'over' => function ($url, ApplyOrder $model) use ($typeName) {
                if (!$model->checkStatus(ApplyOrder::STATUS_OVER)) {
                    return '';
                }
                $options = [
                    'class' => 'btn btn-success',
                ];
                return Html::a($typeName, $url, $options);
            },
        ],
    ],
]);

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-apply-order-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    'extraToolbar' => [
        [
            'content' => Html::a("新增{$typeName}单", ['create'], ['class' => 'btn btn-default'])
        ]
    ]
]);
$simpleDynaGrid->renderDynaGrid();

<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */

/** @var $searchModel backend\models\ApplyOrderSearch */

use backend\widgets\SimpleDynaGrid;
use common\models\ApplyOrder;
use common\models\base\Enum;
use yii\helpers\Html;

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
        'width' => '100px',
        'attribute' => 'created_at',
        'label' => '申请时间',
        'format' => ['datetime', 'php:Y-m-d H:i:s']
    ],
    [
        'width' => '100px',
        'attribute' => 'person.name',
        'label' => '申请人',
    ],
    [
        'attribute' => 'reason',
    ],
    [
        'width' => '100px',
        'attribute' => 'status',
        'value' => function (ApplyOrder $model) {
            return $model->getStatusName();
        }
    ],
    [
        'width' => '150px',
        'attribute' => 'delete_reason',
    ],
];

if (in_array($searchModel->type, [Enum::APPLY_ORDER_TYPE_APPLY, Enum::APPLY_ORDER_TYPE_RETURN])) {
    $columns = array_merge($columns, [
        [
            'width' => '80px',
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
        'width' => '220px',
        'template' => '<p>{detail} {update} {print}</p>  <p>{pass} {delete} {over}</p> <p>{clone}</p>',
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
            'clone' => function ($url, ApplyOrder $model) {
                if (!in_array($model->type, [Enum::APPLY_ORDER_TYPE_INPUT, Enum::APPLY_ORDER_TYPE_OUTPUT, Enum::APPLY_ORDER_TYPE_APPLY])) {
                    return '';
                }
                $options = [
                    'class' => 'btn btn-primary',
                    'data-confirm' => '确认复制该记录？复制的申请单状态为待审核'
                ];
                return Html::a('复用', $url, $options);
            }
        ],
    ],
]);

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-apply-order-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    'extraToolbar' => [
        [
            'content' => Html::a("新增{$typeName}单", ['create'], ['class' => 'btn btn-primary'])
        ]
    ]
]);
$simpleDynaGrid->renderDynaGrid();

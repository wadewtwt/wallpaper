<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */
/** @var $searchModel backend\models\OperateLogSearch */

use backend\widgets\SimpleDynaGrid;
use common\models\OperateLog;
use yii\helpers\Html;

$this->title = '操作日志管理列表';
$this->params['breadcrumbs'] = [
    '操作日志管理',
    $this->title,
];

echo $this->render('_search', [
    'model' => $searchModel,
]);

$columns = [
    [
        'attribute' => 'created_at',
        'format' => ['datetime', 'php:Y-m-d H:i:s']
    ],
    [
        'attribute' => 'createdBy.name',
        'label' => '操作人'
    ],
    [
        'attribute' => 'route',
        'value' => function (OperateLog $model) {
            return $model->getRouteName();
        }
    ],
    [
        'attribute' => 'method',
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '120px',
        'template' => '{more}',
        'buttons' => [
            'more' => function ($url) {
                $options = [
                    'class' => 'btn btn-default show_ajax_modal',
                ];
                return Html::a('更多信息', $url, $options);
            },
        ],
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-operate-log-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
]);
$simpleDynaGrid->renderDynaGrid();

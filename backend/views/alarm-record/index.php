<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */
/** @var $searchModel backend\models\AlarmRecordSearch */

use backend\widgets\SimpleDynaGrid;
use yii\helpers\Html;

$this->title = '仓库列表';
$this->params['breadcrumbs'] = [
    '仓库',
    $this->title,
];

echo $this->render('_search', [
    'model' => $searchModel,
]);

$columns = [
    [
        'attribute' => 'alarm_config_id',
    ],
    [
        'attribute' => 'alarm_at',
    ],
    [
        'attribute' => 'description',
    ],
    [
        'attribute' => 'solve_id',
    ],
    [
        'attribute' => 'solve_at',
    ],
    [
        'attribute' => 'solve_description',
    ],
    [
        'attribute' => 'store_id',
    ],
    [
        'attribute' => 'camera_id',
    ],
    [
        'attribute' => 'type',
    ],
    [
        'attribute' => 'status',
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '150px',
        'template' => '{update} {delete}',
        'buttons' => [
            'update' => function ($url) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                return Html::a('更新', $url, $options);
            },
            'delete' => function ($url) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                return Html::a('删除', $url, $options);
            },
        ],
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-alarm-record-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    'extraToolbar' => [
        [
            'content' => Html::a('新增', ['create'], ['class' => 'btn btn-default'])
        ]
    ]
]);
$simpleDynaGrid->renderDynaGrid();

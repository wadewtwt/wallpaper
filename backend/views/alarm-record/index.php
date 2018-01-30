<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */
/** @var $searchModel backend\models\AlarmRecordSearch */

use backend\widgets\SimpleDynaGrid;
use yii\helpers\Html;
use common\models\AlarmRecord;

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
        'format' => ['datetime', 'php:Y-m-d H:i:s']
    ],
    [
        'attribute' => 'description',
    ],
    [
        'attribute' => 'solve_id',
        'value' => function (AlarmRecord $model) {
            return $model->solve->name;
        }

    ],
    [
        'attribute' => 'solve_at',
        'format' => ['datetime', 'php:Y-m-d H:i:s']
    ],
    [
        'attribute' => 'solve_description',
    ],
    [
        'attribute' => 'store_id',
        'value' => function (AlarmRecord $model) {
            return $model->store->name;
        }
    ],
    [
        'attribute' => 'camera_id',
        'value' => function (AlarmRecord $model) {
            return $model->camera->name;
        }
    ],
    [
        'attribute' => 'type',
        'value' => function (AlarmRecord $model) {
            return $model->alarmType;
        }
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

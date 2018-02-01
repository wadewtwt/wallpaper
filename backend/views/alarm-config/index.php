<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */
/** @var $searchModel backend\models\AlarmConfigSearch */

use backend\widgets\SimpleDynaGrid;
use yii\helpers\Html;
use common\models\AlarmConfig;

$this->title = '联动设置列表';
$this->params['breadcrumbs'] = [
    '联动设置',
    $this->title,
];

echo $this->render('_search', [
    'model' => $searchModel,
]);

$columns = [
    [
        'attribute' => 'id'
    ],
    [
        'attribute' => 'store_id',
        'value' => function (AlarmConfig $model) {
            return $model->store->name;
        }
    ],
    [
        'attribute' => 'camera_id',
        'value' => function (AlarmConfig $model) {
            return $model->camera->name;
        }
    ],
    [
        'attribute' => 'type',
        'value' => function (AlarmConfig $model) {
            return $model->getAlarmType();
        }
    ],
    [
        'attribute' => 'status',
        'value' => function (AlarmConfig $model) {
            return $model->getAlarmStatus();
        }
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '300px',
        'template' => '{update} {normal} {stop} {delete}',
        'buttons' => [
            'update' => function ($url) {
                $options = [
                    'class' => 'btn btn-default show_ajax_modal',
                ];
                return Html::a('更新', $url, $options);
            },
            'normal' => function ($url, $model) {
                $options = [
                    'class' => 'btn btn-success',
                    'data-method' => 'post',
                    'data-confirm' => '确定启用该联动？'
                ];
                if ($model->status == AlarmConfig::STATUS_NORMAL) {
                    return '';
                }
                return Html::a('启用', $url, $options);
            },
            'stop' => function ($url, $model) {
                $options = [
                    'class' => 'btn btn-warning',
                    'data-method' => 'post',
                    'data-confirm' => '确定停用该联动？'
                ];
                if ($model->status == AlarmConfig::STATUS_STOP) {
                    return '';
                }
                return Html::a('停用', $url, $options);
            },
            'delete' => function ($url) {
                $options = [
                    'class' => 'btn btn-danger',
                    'data-method' => 'post',
                    'data-confirm' => '确定删除该联动？'
                ];
                return Html::a('删除', $url, $options);
            },
        ],
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-alarm-config-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    'extraToolbar' => [
        [
            'content' => Html::a('新增', ['create'], ['class' => 'btn btn-default show_ajax_modal'])
        ]
    ]
]);
$simpleDynaGrid->renderDynaGrid();

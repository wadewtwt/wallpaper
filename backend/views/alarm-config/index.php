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
        'attribute' => 'store.name',
        'label' => '所属仓库',
    ],
    [
        'attribute' => 'camera.name',
        'label' => '摄像头名称',
    ],
    [
        'attribute' => 'type',
        'value' => function (AlarmConfig $model) {
            return $model->getAlarmType();
        }
    ],
    [
        'attribute' => 'remark',
    ],
    [
        'attribute' => 'status',
        'value' => function (AlarmConfig $model) {
            return $model->getAlarmStatus();
        }
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '180px',
        'template' => '{change-status} {delete}',
        'buttons' => [
            'change-status' => function ($url, $model) {
                if ($model->status == AlarmConfig::STATUS_NORMAL) {
                    $msg = '停用';
                    $optionsClass = 'btn btn-danger';
                } else {
                    $msg = '启用';
                    $optionsClass = 'btn btn-success';
                }
                $options = [
                    'class' => $optionsClass,
                    'data-method' => 'post',
                    'data-confirm' => '确认' . $msg . '？'
                ];
                return Html::a($msg, $url, $options);
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

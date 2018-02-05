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
        'attribute' => 'store_id',
        'value' => function (AlarmRecord $model) {
            return $model->store->name;
        },
        'label' => '所属仓库'
    ],
    [
        'attribute' => 'camera_id',
    ],
    [
        'attribute' => 'type',
        'value' => function (AlarmRecord $model) {
            return $model->getTypeName();
        }
    ],
    [
        'attribute' => 'description',
    ],
    [
        'attribute' => 'solve_id',
        'value' => function (AlarmRecord $model) {
            if ($model->solve_id) {
                return $model->solve->name;
            }
            return '';
        }
    ],
    [
        'attribute' => 'solve_at',
        'value' => function (AlarmRecord $model) {
            if ($model->solve_at) {
                return date('Y-m-d H:i:s', $model->solve_at);
            }
            return '';
        }
    ],
    [
        'attribute' => 'solve_description',
    ],
    [
        'attribute' => 'status',
        'value' => function (AlarmRecord $model) {
            return $model->getStatusName();
        }
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '150px',
        'template' => '{solve}',
        'buttons' => [
            'solve' => function ($url, $model) {
                if ($model->status == AlarmRecord::STATUS_SOLVED) {
                    return '';
                }
                $options = [
                    'class' => 'btn btn-default show_ajax_modal',
                ];
                return Html::a('处理', $url, $options);
            },
        ],
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-alarm-record-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,

]);
$simpleDynaGrid->renderDynaGrid();

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
        'width' => '150px',
        'attribute' => 'alarm_at',
        'format' => ['datetime', 'php:Y-m-d H:i:s']
    ],
    [
        'width' => '80px',
        'attribute' => 'store_id',
        'value' => function (AlarmRecord $model) {
            return $model->store->name;
        },
        'label' => '所属仓库'
    ],
    [
        'width' => '90px',
        'attribute' => 'camera_id',
        'label' => '摄像头名称',
        'value' => function (AlarmRecord $model) {
            return $model->camera->name;
        }
    ],
    [
        'width' => '100px',
        'attribute' => 'type',
        'value' => function (AlarmRecord $model) {
            return $model->getTypeName();
        }
    ],
    [
        'attribute' => 'description',
    ],
    [
        'width' => '250px',
        'label' => '处理情况',
        'value' => function (AlarmRecord $model){
            $html = [];
            if ($model->solve_id) {
                $html[] = $model->getAttributeLabel('solve_id') . ':' . $model->solve->name;
            }
            if ($model->solve_id) {
                $html[] = $model->getAttributeLabel('solve_at').':'.date('Y-m-d H:i:s', $model->solve_at);
            }
            if ($model->solve_id) {
                $html[] = $model->getAttributeLabel('solve_description').':'.$model->solve_description;
            }
            return implode('<br>',$html);
        },
        'format' => 'html'
    ],
    [
        'width' => '80px',
        'attribute' => 'status',
        'value' => function (AlarmRecord $model) {
            return $model->getStatusName();
        }
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '100px',
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

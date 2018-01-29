<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */
/** @var $searchModel backend\models\ResourceDetailSearch */

use backend\widgets\SimpleDynaGrid;
use common\models\ResourceDetail;
use yii\helpers\Html;

$typeName = $searchModel->getTypeName();
$controllerId = $this->context->id;

$this->title = $typeName . '明细列表';
$this->params['breadcrumbs'] = [
    $typeName . '管理',
    $this->title,
];

echo $this->render('_search', [
    'model' => $searchModel,
]);

$columns = [
    [
        'attribute' => 'resource.name',
    ],
    [
        'attribute' => 'container.name',
        'label' => '货位'
    ],
    [
        'attribute' => 'is_online',
        'value' => function (ResourceDetail $model) {
            return $model->is_online ? '在线' : '离线';
        }
    ],
    [
        'attribute' => 'online_change_at',
        'format' => ['date', 'php:Y-m-d']
    ],
    [
        'attribute' => 'maintenance_at',
        'format' => ['date', 'php:Y-m-d H:i:s']
    ],
    [
        'attribute' => 'scrap_at',
        'format' => ['date', 'php:Y-m-d H:i:s']
    ],
    [
        'attribute' => 'quantity',
    ],
    [
        'attribute' => 'status',
        'value' => function (ResourceDetail $model) {
            return $model->getStatusName();
        }
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '150px',
        'template' => '{detail}',
        'buttons' => [
            'detail' => function ($url, $model) use ($controllerId) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                return Html::a('操作记录', ["/{$controllerId}-operation", 'device_id' => $model->id], $options);
            },
        ],
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-resource-detail-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
]);
$simpleDynaGrid->renderDynaGrid();

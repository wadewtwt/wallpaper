<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */
/** @var $searchModel backend\models\ResourceDetailSearch */

use backend\models\ResourceDetailOperationSearch;
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
        'attribute' => 'id',
    ],
    [
        'attribute' => 'resource.name',
    ],
    [
        'attribute' => 'container.name',
        'label' => '货位'
    ],
    [
        'label' => '标签',
        'width' => '300px',
        'value' => function (ResourceDetail $model) {
            $html = [];
            $html[] = '有源标签：' . $model->tag_active;
            $html[] = '无源标签：' . $model->tag_passive;
            return implode('<br>', $html);
        },
        'format' => 'html'
    ],
    [
        'label' => '在线离线',
        'width' => '200px',
        'value' => function (ResourceDetail $model) {
            $html = [];
            $html[] = $model->is_online
                ? '<span class="fa fa-check-circle text-green"></span> 在线'
                : '<span class="fa fa-exclamation-circle text-red"></span> 离线';
            $html[] = '时间：' . date('Y-m-d H:i:s', $model->online_change_at);
            return implode('<br>', $html);
        },
        'format' => 'html'
    ],
    [
        'label' => '状态',
        'width' => '200px',
        'value' => function (ResourceDetail $model) {
            $html = [];
            if ($model->status == ResourceDetail::STATUS_NORMAL && !$model->is_online) {
                // 应该在库的，但是扫描不在线的
                $html[] = '异常';
            } else {
                $html[] = $model->getStatusName();
            }
            $html[] = '时间：' . date('Y-m-d H:i:s', $model->updated_at);
            return implode('<br>', $html);
        },
        'format' => 'html'
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
        'attribute' => 'remark',
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '150px',
        'template' => '{detail}',
        'buttons' => [
            'detail' => function ($url, ResourceDetail $model) use ($controllerId) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                $url = ["/{$controllerId}-operation", Html::getInputName(new ResourceDetailOperationSearch(), 'resource_detail_id') => $model->id];
                return Html::a('操作记录', $url, $options);
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

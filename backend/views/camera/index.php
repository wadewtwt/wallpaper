<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */
/** @var $searchModel backend\models\CameraSearch */

use backend\widgets\SimpleDynaGrid;
use yii\helpers\Html;
use common\models\Camera;

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
        'attribute' => 'id'
    ],
    [
        'attribute' => 'store_id',
        'value' => function (Camera $model) {
            return $model->store->name;
        },
        'label' => '所属仓库'
    ],
    [
        'attribute' => 'name',
    ],
    [
        'width' => '120px',
        'attribute' => 'ip',
    ],
    [
        'attribute' => 'port',
    ],
    [
        'attribute' => 'username',
    ],
    [
        'attribute' => 'device_no',
    ],
    [
        'attribute' => 'remark',
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '150px',
        'template' => '{update} {delete}',
        'buttons' => [
            'update' => function ($url) {
                $options = [
                    'class' => 'btn btn-default show_ajax_modal',
                ];
                return Html::a('更新', $url, $options);
            },
            'delete' => function ($url) {
                $options = [
                    'class' => 'btn btn-danger',
                    'data-method' => 'post',
                    'data-confirm' => '确定删除该摄像头设备？'
                ];
                return Html::a('删除', $url, $options);
            },
        ],
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-camera-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    'extraToolbar' => [
        [
            'content' => Html::a('新增', ['create'], ['class' => 'btn btn-default show_ajax_modal'])
        ]
    ]
]);
$simpleDynaGrid->renderDynaGrid();

<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */
/** @var $searchModel backend\models\TemperatureSearch */

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
        'attribute' => 'store_id',
    ],
    [
        'attribute' => 'ip',
    ],
    [
        'attribute' => 'port',
    ],
    [
        'attribute' => 'device_no',
    ],
    [
        'attribute' => 'down_limit',
    ],
    [
        'attribute' => 'up_limit',
    ],
    [
        'attribute' => 'current',
    ],
    [
        'attribute' => 'current_updated_at',
    ],
    [
        'attribute' => 'remark',
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
                    'class' => 'btn btn-danger',
                    'data-method' => 'post',
                    'data-confirm' => '确定删除该温湿度设备？'
                ];
                return Html::a('删除', $url, $options);
            },
        ],
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-temperature-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    'extraToolbar' => [
        [
            'content' => Html::a('新增', ['create'], ['class' => 'btn btn-default'])
        ]
    ]
]);
$simpleDynaGrid->renderDynaGrid();

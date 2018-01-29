<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */

/** @var $searchModel backend\models\ContainerSearch */

use backend\widgets\SimpleDynaGrid;
use common\models\Container;
use yii\helpers\Html;

$this->title = '货位管理列表';
$this->params['breadcrumbs'] = [
    '货位管理',
    $this->title,
];

echo $this->render('_search', [
    'model' => $searchModel,
]);

$columns = [
    [
        'attribute' => 'name',
    ],
    [
        'attribute' => 'total_quantity',
    ],
    [
        'label' => '空闲',
        'value' => function (Container $model) {
            return $model->getFreeQuantity();
        }
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'width' => '300px',
        'template' => '{delete}',
        'buttons' => [
            'delete' => function ($url) {
                $options = [
                    'class' => 'btn btn-danger',
                    'data-method' => 'post',
                    'data-confirm' => '确定删除该货架？'
                ];
                return Html::a('删除货架', $url, $options);
            },
        ],
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-container-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    'extraToolbar' => [
        [
            'content' => Html::a('新增', ['create'], ['class' => 'btn btn-default show_ajax_modal'])
        ],
    ]
]);
$simpleDynaGrid->renderDynaGrid();

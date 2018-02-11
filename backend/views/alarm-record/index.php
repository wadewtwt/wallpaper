<?php
/** @var $this yii\web\View */
/** @var $dataProvider common\components\ActiveDataProvider */

/** @var $searchModel backend\models\AlarmRecordSearch */

use backend\widgets\SimpleDynaGrid;
use common\models\Admin;
use yii\helpers\Html;
use common\models\AlarmRecord;
use yii\helpers\Url;

/** @var Admin $admin */
$admin = Yii::$app->user->identity;
$isSuperAdmin = $admin->admin_role == Admin::ADMIN_ROLE_SUPER_ADMIN;

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
        'width' => '100px',
        'attribute' => 'alarm_at',
        'format' => ['datetime', 'php:Y-m-d H:i:s']
    ],
    [
        'width' => '80px',
        'attribute' => 'store.name',
        'label' => '所属仓库'
    ],
    [
        'width' => '100px',
        'attribute' => 'camera.name',
        'label' => '摄像头名称',
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
        'value' => function (AlarmRecord $model) {
            return $model->getDescriptionHtmlFormat();
        },
        'format' => 'html',
    ],
    [
        'width' => '250px',
        'label' => '处理情况',
        'value' => function (AlarmRecord $model) {
            $html = [];
            if ($model->solve_id) {
                $html[] = $model->getAttributeLabel('solve_id') . ':' . $model->solve->name;
            }
            if ($model->solve_id) {
                $html[] = $model->getAttributeLabel('solve_at') . ':' . date('Y-m-d H:i:s', $model->solve_at);
            }
            if ($model->solve_id) {
                $html[] = $model->getAttributeLabel('solve_description') . ':' . $model->solve_description;
            }
            return implode('<br>', $html);
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
    [
        'class' => '\kartik\grid\CheckboxColumn',
    ],
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-alarm-record-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    'extraToolbar' => [
        [
            'content' =>
                Html::button('批量处理', [
                    'class' => 'btn btn-success check_operate_need_confirm_and_modal',
                    'data-url' => Url::to(['batch-solve']),
                ])
        ],
        [
            'content' =>
                $isSuperAdmin ? Html::button('生成申领单', [
                    'class' => 'btn btn-primary simple_check_operate',
                    'data-url' => Url::to(['generate-apply-order']),
                    'data-form' => '1',
                ]) : ''
        ],
    ],
]);
$simpleDynaGrid->renderDynaGrid();

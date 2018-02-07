<?php
/** 出入库监控 */
/** @var $this \yii\web\View */

use common\models\ApplyOrder;
use common\models\base\Enum;
use yii\helpers\Html;

$models = ApplyOrder::find()
    ->where(['status' => [// 排除了“已完成”，“已归还”，“作废”
        ApplyOrder::STATUS_APPLYING,
        ApplyOrder::STATUS_OPERATE_PRINT,
        ApplyOrder::STATUS_OPERATE_UPDATE,
        ApplyOrder::STATUS_OPERATE_RETURN,
        ApplyOrder::STATUS_AUDITED
    ]])
    ->limit(10)
    ->orderBy(['created_at' => SORT_DESC])
    ->all();
?>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">申请单监控</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
            </button>
        </div>
    </div>

    <div class="box-body">
        <div class="table-responsive">
            <table class="table no-margin">
                <thead>
                <tr>
                    <th>事件</th>
                    <th>申请人</th>
                    <th>时间</th>
                    <th>理由</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($models as $model) { ?>
                    <tr>
                        <td><span class="label label-info"><?= $model->typeName ?></span></td>
                        <td><?= $model->person->name ?></td>
                        <td><?= date('Y-m-d H:i:s', $model->updated_at) ?></td>
                        <td class="text-primary"><?= $model->reason ?></td>
                        <td>
                            <span class="label label-default"><?= $model->statusName ?></span>
                        </td>
                        <td>
                            <?php
                            if ($model->type == Enum::APPLY_ORDER_TYPE_INPUT) {
                                $url = ["/apply-order-input"];
                            } elseif ($model->type == Enum::APPLY_ORDER_TYPE_OUTPUT) {
                                $url = ["/apply-order-output"];
                            } elseif ($model->type == Enum::APPLY_ORDER_TYPE_APPLY) {
                                $url = ["/apply-order-apply"];
                            } elseif ($model->type == Enum::APPLY_ORDER_TYPE_RETURN) {
                                $url = ["/apply-order-return"];
                            } else {
                                $url = '';
                            }
                            echo Html::a('查看', $url, $btnOptions);
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

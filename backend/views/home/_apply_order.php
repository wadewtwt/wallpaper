<?php
/** 出入库监控 */
/** @var $this \yii\web\View */

use common\models\ApplyOrder;

$models = ApplyOrder::find()->limit(5)
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
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

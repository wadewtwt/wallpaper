<?php
/** 消耗品详细 监控 */
/** @var $this \yii\web\View */

use common\models\ExpendableDetail;

$countExpendableDetails = ExpendableDetail::countList();
?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">消耗品详细 监控</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>

    <div class="box-body">
        <div class="table-responsive">
            <table class="table no-margin">
                <thead>
                <tr>
                    <th>时间</th>
                    <th>名称</th>
                    <th>数量</th>
                    <th>状态</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($countExpendableDetails as $val){?>
                    <tr>
                        <td>
                            <span class="description-header"><?= date('Y-m-d H:i:s', $val->created_at) ?></span>
                        </td>
                        <td class="text-primary"><?= $val->resource->name?></td>
                        <td class="text-primary"><?= $val->quantity?></td>
                        <td><span class="label label-default"><?= $val->OperationName?></span></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>

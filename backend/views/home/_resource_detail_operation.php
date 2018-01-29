<?php
/** 消耗品详细 监控 */
/** @var $this \yii\web\View */
/** @var $resourceType integer */
/** @var $title string */

use common\models\ResourceDetailOperation;

$models = ResourceDetailOperation::find()
    ->alias('a')
    ->joinWith('resourceDetail b')
    ->where(['b.type' => $resourceType])
    ->orderBy(['created_at' => SORT_DESC])
    ->limit(5)
    ->all();
?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $title ?></h3>

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
                <?php foreach ($models as $model) { ?>
                    <tr>
                        <td>
                            <span class="description-header"><?= date('Y-m-d H:i:s', $model->created_at) ?></span>
                        </td>
                        <td class="text-primary"><?= $model->resourceDetail->resource->name ?></td>
                        <td class="text-primary"><?= $model->quantity ?></td>
                        <td><span class="label label-default"><?= $model->getOperationName() ?></span></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

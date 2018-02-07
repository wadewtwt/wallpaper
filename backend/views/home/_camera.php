<?php
/** 摄像头设备 */
/** @var $this \yii\web\View */

use common\models\Camera;
use yii\helpers\Html;

$models = Camera::find()
    ->orderBy(['created_at' => SORT_DESC])
    ->all();
?>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">摄像头设备</h3>

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
                    <th>所属仓库</th>
                    <th>名称</th>
                    <th>备注</th>
                    <th>设备号</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($models as $model) { ?>
                    <tr>
                        <td><span class="label label-info"><?= $model->store->name ?></span></td>
                        <td><?= $model->name ?></td>
                        <td><?= $model->remark ?></td>
                        <td class="text-primary"><?= $model->device_no ?></td>
                        <td>
                            <?= Html::a('查看', '/camera', $btnOptions); ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
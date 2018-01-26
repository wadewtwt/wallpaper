<?php
/** 装备明细监控 */
/** @var $this \yii\web\View */
use common\models\DeviceDetail;

$countDeviceDetails = DeviceDetail::countList();
?>

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">设备详细 监控</h3>

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
                        <th>时间</th>
                        <th>名称</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($countDeviceDetails as $val) { ?>
                        <tr>
                            <td>
                                <span class="description-header"><?= date('Y-m-d H:i:s', $val->created_at) ?></span>
                            </td>
                            <td><?= $val->device->resource->name ?></td>
                            <td><span class="label label-default"><?= $val->operationName ?></span></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

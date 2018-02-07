<?php
/** 报警记录 */
/** @var $this \yii\web\View */

use common\models\AlarmRecord;
use yii\helpers\Html;

$models = AlarmRecord::find()->limit(10)
    ->where(['status' => AlarmRecord::STATUS_NORMAL])// 筛选出待处理的记录
    ->orderBy(['updated_at' => SORT_DESC])
    ->all();
?>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">报警记录</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
            </button>
        </div>
    </div>

    <div class="box-body">
        <div class="table-responsive">
            <?php
            if ($models) {
                ?>
                <table class="table no-margin">
                    <thead>
                    <tr>
                        <th>报警时间</th>
                        <th>报警描述</th>
                        <th>所属仓库</th>
                        <th>报警类型</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($models as $model) { ?>
                        <tr>
                            <td><span class="label label-warning"><?= date('Y-m-d H:i:s', $model->alarm_at) ?></span>
                            </td>
                            <td><?= $model->description ?></td>
                            <td class="text-primary"><?= $model->store->name ?></td>
                            <td><?= $model->typeName ?></td>
                            <td><?= Html::a('查看', '/alarm-record', $btnOptions) ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php
            } else {
                echo "暂无数据";
            }
            ?>
        </div>
    </div>
</div>

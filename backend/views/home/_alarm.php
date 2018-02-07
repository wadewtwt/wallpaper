<?php
/** @var $this \yii\web\View */

use common\models\AlarmRecord;
use yii\helpers\Html;

$models = AlarmRecord::find()->with('store')
    ->where(['status' => AlarmRecord::STATUS_NORMAL])// 筛选出待处理的记录
    ->orderBy(['updated_at' => SORT_DESC])
    ->limit(10)
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
            <?php if ($models) : ?>
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
                    <?php foreach ($models as $model): ?>
                        <tr>
                            <td><?= date('Y-m-d H:i:s', $model->alarm_at) ?></td>
                            <td><?= $model->getDescriptionHtmlFormat() ?></td>
                            <td><?= $model->store->name ?></td>
                            <td><?= $model->typeName ?></td>
                            <td><?= Html::a('查看', '/alarm-record', $btnOptions) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p class="text-center">当前无任何异常</p>
            <?php endif; ?>
        </div>
    </div>
</div>

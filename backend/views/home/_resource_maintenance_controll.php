<?php
/** 设备消耗品监控 */
/** @var $this \yii\web\View */
/** @var $title string */

use common\models\ResourceDetail;
use yii\helpers\Html;
use common\models\Resource;
use backend\models\ResourceDetailSearch;

$nowTime = time();
$sevenDaysLaterTime = time() + 7 * 86400;
$models = ResourceDetail::find()
    ->where(['status' => ResourceDetail::STATUS_NORMAL])
    ->andWhere(['<', 'maintenance_at', time() + 7 * 86400])// 报废时间小于七天后的那个时间戳
    ->orderBy(['scrap_at' => SORT_ASC])
    ->limit(10)
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
                    <th>类型</th>
                    <th>名称</th>
                    <th>货区</th>
                    <th>维护时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($models as $model) { ?>
                    <tr>
                        <td>
                            <span class="description-header"><?= $model->typeName ?></span>
                        </td>
                        <td class="text-primary"><?= $model->resource->name ?></td>
                        <td><?= $model->container->name ?></td>
                        <td class="text-danger"><?= date('Y-m-d H:i:s', $model->maintenance_at) ?></td>
                        <td>
                            <?php
                            if ($model->type == Resource::TYPE_EXPENDABLE) {
                                $url = ["/expendable-detail", Html::getInputName(new ResourceDetailSearch(), 'resource_id') => $model->resource_id];
                            } elseif ($model->type == Resource::TYPE_DEVICE) {
                                $url = ["/device-detail", Html::getInputName(new ResourceDetailSearch(), 'resource_id') => $model->resource_id];
                            } else {
                                $url = '';
                            }
                            echo Html::a('明细', $url, $btnOptions);
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
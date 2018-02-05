<?php
/** 报警记录 */
 /** @var $this \yii\web\View */

 use common\models\AlarmRecord;
$models = AlarmRecord::find()->limit(5)
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
                <table class="table no-margin">
                    <thead>
                    <tr>
                        <th>报警配置ID</th>
                        <th>报警时间</th>
                        <th>报警描述</th>
                        <th>处理人</th>
                        <th>处理时间</th>
                        <th>处理描述</th>
                        <th>所属仓库</th>
                        <th>摄像头ID</th>
                        <th>报警类型</th>
                        <th>状态</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($models as $model) { ?>
                    <tr>
                        <td><?= $model->alarm_config_id?></td>
                        <td><span class="label label-warning"><?= date('Y-m-d H:i:s', $model->alarm_at) ?></span></td>
                        <td><?= $model->description?></td>
                        <td>
                            <?php if($model->solve_id){
                                echo $model->solve->name;
                            }?>
                        </td>
                        <td><span class="label label-info"><?= date('Y-m-d H:i:s', $model->alarm_at) ?></span></td>
                        <td>
                            <span class="description-header"><?= $model->solve_description?></span>
                        </td>
                        <td class="text-primary"><?= $model->store->name?></td>
                        <td><?= $model->camera->name?></td>
                        <td><?= $model->TypeName?></td>
                        <td><span class="label label-default"><?= $model->statusName?></span></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php
/** 摄像头设备 */
/** @var $this \yii\web\View */

use common\models\Camera;
use yii\helpers\Html;

$models = Camera::find()
    ->orderBy(['created_at' => SORT_DESC])
    ->all();
?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">摄像头设备</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <table class="table table-condensed">
            <tbody>
            <tr>
                <th>#所属仓库</th>
                <th>名称</th>
                <th style="width: 40px">操作</th>
            </tr>


            <?php foreach ($models as $model) { ?>
                <tr>
                    <td><span class="badge bg-yellow"><?= $model->store->name ?></span></td>
                    <td><span class="badge bg-red"><?= $model->name ?></span></td>
                    <td>
                        <?= Html::a('查看', '/camera', ['class' => 'btn bg-danger btn-sm']); ?>
                    </td>
                </tr>
            <?php } ?>

            </tbody></table>
    </div>
    <!-- /.box-body -->
</div>


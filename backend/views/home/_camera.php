<?php
/** @var $this \yii\web\View */

use common\models\Camera;
use yii\helpers\Html;
use yii\helpers\Url;

$models = Camera::find()->all();
?>
<?php foreach ($models as $model) : ?>
    <div class="col-md-4">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-video-camera"></i></span>
            <div class="info-box-content">
                <span class="info-box-text clearfix">
                    <?= "【{$model->store->name}】{$model->name}" ?>
                    <?= Html::button('查看', ['class' => 'btn btn-success btn-sm pull-right camera_view', 'data-id' => $model->id]) ?>
                </span>
                <small class="text-muted">
                    <?= "ip:{$model->ip} 端口:{$model->port} 设备号:{$model->device_no}" ?>
                </small>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php
$url = Url::to(['/api/camera-view']);
$js = <<<JS
$('.camera_view').click(function() {
    var id = $(this).data('id');
    $.post('{$url}', {id: id}, function(data) {
        //alert(data);
    });
});
JS;
$this->registerJs($js);

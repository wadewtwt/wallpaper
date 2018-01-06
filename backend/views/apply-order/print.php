<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\ApplyOrder */

echo $this->render('detail', [
    'model' => $model,
    'isPrint' => true,
]);
?>

<div class="container">
    <div class="btn btn-success pull-right hidden-print" onclick="window.print()">打印</div>
</div>

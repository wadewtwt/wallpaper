<?php
/** @var $this \yii\web\View */
/** @var $applyOrder \common\models\ApplyOrder */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

\common\assets\VueAssets::register($this);

$form = ActiveForm::begin();
?>
    <div class="invoice">
        <div class="container">
            <div class="page-header">
                <h1 class="text-center"><?= $applyOrder->getTypeName() ?>单</h1>
            </div>
            <?= $this->render('_apply_order', [
                'model' => $applyOrder,
            ]) ?>
            <?= $this->render('_apply_order_detail', [
                'models' => $applyOrder->applyOrderDetails,
            ]) ?>

            <p class="lead">详情</p>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="text-center">序号</th>
                    <th class="text-center">资源名</th>
                    <th class="text-center">货位名</th>
                    <th class="text-center">无源标签</th>
                    <th class="text-center">数量</th>
                </tr>
                </thead>
                <tbody class="resource-container" v-cloak>
                <tr v-for="(item, index) in listData" class="text-center">
                    <td>{{ index+1 }}</td>
                    <td>
                        <select v-model="item.resource_id"
                                :name="'ApplyOrderResource[' + index + '][resource_id]'"
                                class="form-control" readonly>
                            <option v-for="resource in resourceData" :value="resource.id">{{ resource.name }}</option>
                        </select>
                    </td>
                    <td>
                        <select v-model="item.container_id"
                                :name="'ApplyOrderResource[' + index + '][container_id]'"
                                class="form-control" readonly>
                            <option v-for="container in containerData" :value="container.id">{{ container.name }}</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" v-model="item.tag_passive"
                               :name="'ApplyOrderResource[' + index + '][tag_passive]'"
                               class="form-control" readonly>
                    </td>
                    <td>
                        <input type="text" v-model="item.quantity"
                               :name="'ApplyOrderResource[' + index + '][quantity]'"
                               class="form-control" readonly>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="box-footer container">
            <a href="javascript:history.go(-1)" class="btn btn-default">取消</a>
            <input type="submit" value="完成" class="btn btn-primary">
        </div>

        <?php
        ActiveForm::end();
        ?>
    </div>
    <script>

    </script>
<?php
$resourceTagPassiveUrl = Url::to(['/api/apply-order-over']);
$resourceDataUrl = Url::to(['/api/resource-data']);
$containerDataUrl = Url::to(['/api/container-data']);
$js = <<<JS
new Vue({
    el: '.resource-container',
    data: {
        listData: [],
        resourceData: [],
        containerData: [],
    },
    methods: {
        getListData: function () {
            var _this = this;
            $.get('{$resourceTagPassiveUrl}', {
                'tag_passives': '17 12 12 e9 23 90 11,17 12 12 e9 23 90 12'
            }, function(data) {
                _this.listData = data;
            }, 'json')
        },
        getResourceData: function() {
            var _this = this;
            $.get('{$resourceDataUrl}', {}, function(data) {
                _this.resourceData = data;
            }, 'json')
        },
        getContainerData: function() {
            var _this = this;
            $.get('{$containerDataUrl}', {}, function(data) {
                _this.containerData = data;
            }, 'json')
        }
    },
    mounted: function () {
        this.getResourceData();
        this.getContainerData();
        setInterval(this.getListData, 2000);
    }
});
JS;
$this->registerJs($js);
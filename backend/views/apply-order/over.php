<?php
/** @var $this \yii\web\View */
/** @var $applyOrder \common\models\ApplyOrder */
/** @var $applyOrderResources [] \common\models\ApplyOrderResource[] */
/** @var $tagSessionAutoStart bool */
/** @var $applyOrderResourceScenario string */

use common\models\ApplyOrderResource;
use common\models\base\ConfigString;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

\common\assets\VueAssets::register($this);
\backend\assets\TagReadAsset::register($this);

$form = ActiveForm::begin([
    'id' => 'form',
]);
?>
    <div id="vue-container" class="invoice" v-cloak>
        <div class="page-header">
            <h1 class="text-center"><?= $applyOrder->getTypeName() ?>单</h1>
        </div>
        <?= $this->render('_apply_order', [
            'model' => $applyOrder,
        ]) ?>
        <?= $this->render('_apply_order_detail', [
            'models' => $applyOrder->applyOrderDetails,
            'showReal' => $applyOrderResourceScenario == ApplyOrderResource::SCENARIO_RETURN,
        ]) ?>

        <p class="lead">详情</p>
        <p>
            <button class="btn btn-primary" type="button"
                    @click="tagSessionIsStart=!tagSessionIsStart">{{ tagSessionIsStart?'停止':'开启' }}扫描
            </button>
            <span v-if="tagSessionIntervalTime">最后扫描时间：{{ tagSessionIntervalTime }}</span>
        </p>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="text-center">序号</th>
                <th class="text-center">资源名</th>
                <th class="text-center">货位名</th>
                <th class="text-center">无源标签</th>
                <th class="text-center">数量</th>
                <th class="text-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index) in listData" class="text-center">
                <td>{{ index+1 }}</td>
                <td class="form-group" :class="{'has-error': validateErrors[index+'-resource_id']}">
                    <select v-model="item.resource_id"
                            :name="'ApplyOrderResource[' + index + '][resource_id]'"
                            class="form-control" readonly>
                        <option v-for="resource in resourceData" :value="resource.id">{{ resource.name }}</option>
                    </select>
                    <div class="help-block help-block-error">{{ validateErrors[index+'-resource_id'] }}</div>
                </td>
                <td class="form-group" :class="{'has-error': validateErrors[index+'-container_id']}">
                    <select v-model="item.container_id"
                            :name="'ApplyOrderResource[' + index + '][container_id]'"
                            class="form-control" readonly>
                        <option v-for="container in containerData" :value="container.id">{{ container.name }}</option>
                    </select>
                    <div class="help-block help-block-error">{{ validateErrors[index+'-container_id'] }}</div>
                </td>
                <td class="form-group" :class="{'has-error': validateErrors[index+'-tag_passive']}">
                    <input type="text" v-model="item.tag_passive"
                           :name="'ApplyOrderResource[' + index + '][tag_passive]'"
                           class="form-control" readonly>
                    <div class="help-block help-block-error">{{ validateErrors[index+'-tag_passive'] }}</div>
                </td>
                <td class="form-group" :class="{'has-error': validateErrors[index+'-quantity']}">
                    <input type="text" v-model="item.quantity"
                           :name="'ApplyOrderResource[' + index + '][quantity]'"
                           class="form-control" readonly>
                    <div class="help-block help-block-error">{{ validateErrors[index+'-quantity'] }}</div>
                </td>
                <td>
                    <button class="btn btn-danger" type="button" @click="remove(index)">删除</button>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="box-footer">
            <a href="javascript:history.go(-1)" class="btn btn-default">取消</a>
            <button @click="submit" type="button" class="btn btn-primary">完成</button>
        </div>

        <?php
        ActiveForm::end();
        ?>
    </div>
<?php
$resourceTagPassiveUrl = Url::to(['/api/apply-order-over']);
$resourceDataUrl = Url::to(['/api/resource-data']);
$containerDataUrl = Url::to(['/api/container-data']);
$tagReadBaseUrl = Yii::$app->params[ConfigString::PARAMS_TAG_READ_URL];
$tagSessionId = $applyOrder->id . time();
$resourceData = new JsExpression(Json::encode($applyOrderResources));
$tagSessionAutoStart = intval($tagSessionAutoStart);
$js = <<<JS
new Vue({
    el: '#vue-container',
    data: {
        listData: {$resourceData},
        validateErrors: {},
        resourceData: [],
        containerData: [],
        tagSessionId: '{$tagSessionId}',
        tagSessionIsStart: false,
        tagPassives: '',
        tagSessionInterval: null,
        tagSessionIntervalTime: null,
    },
    watch: {
        tagSessionIsStart: function() {
            if(this.tagSessionIsStart) {
                this.tagSessionStart();
                this.tagPassives = ''; // 开启扫描后重置所有标签数据
                this.tagSessionInterval = setInterval(this.getTagPassives, 2000);
            } else {
                clearInterval(this.tagSessionInterval);
                this.tagSessionEnd();
            }
        },
        tagPassives: function() {
            this.getListData()
        }
    },
    methods: {
        tagSessionStart: function () {
            tagRead.beginSession(this.tagSessionId);
        },
        tagSessionEnd: function () {
            tagRead.endSession(this.tagSessionId);
        },
        getTagPassives: function() {
            var _this = this;
            tagRead.session(this.tagSessionId, function(data) {
                _this.tagPassives = data.join(',')
                _this.tagSessionIntervalTime = (new Date()).toLocaleString();
            })
        },
        getListData: function () {
            var _this = this;
            $.get('{$resourceTagPassiveUrl}', {
                'tag_passives': this.tagPassives
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
        },
        remove: function(index) {
            if(!this.tagSessionIsStart) {
                this.listData.splice(index, 1);
                this.validateErrors = {}
            } else {
                alert('请先停止扫描后再进行删除');
            }
        },
        submit: function() {
            var _this = this;
            var params = $('#form').serialize()+ '&ajax=1';
            $.post('', params, function(data) {
                if(data !== null && typeof data === 'object' && Object.prototype.toString.call(data) === '[object Object]') {
                    $.each(data, function(key, value) {
                        // key: applyorderresource-0-resource_id
                        var keyArr= key.split('-');
                        // vm.validateErrors['0-resource_id'] = 'ID 必须'
                        _this.\$set(_this.validateErrors, keyArr[1] + '-'+keyArr[2], value[0])
                    });
                }else {
                    _this.tagSessionEnd();
                    $('#form').submit();
                }
            }, 'json');
        }
    },
    mounted: function () {
        this.getResourceData();
        this.getContainerData();
        tagRead.baseUrl = '{$tagReadBaseUrl}';
        this.tagSessionIsStart = {$tagSessionAutoStart};
    }
});
JS;
$this->registerJs($js);
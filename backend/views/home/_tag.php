<?php
/** @var $this \yii\web\View */

use common\models\base\ConfigString;

\common\assets\VueAssets::register($this);
\backend\assets\TagReadAsset::register($this);

?>
    <div class="vue-tag-container" v-cloak>
        <div v-for="item in dataList" class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon" :class="'bg-' + item.color">
                    <i class="fa fa-tablet"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ item.name }}</span>
                    <h5 class="info-box-number" :class="'text-' + item.color">
                        {{ item.serverOk ? (item.deviceOk ? '在线' : '服务正常，但未联通设备') : '服务未开启' }}
                    </h5>
                </div>
            </div>
        </div>
    </div>
<?php
$tagReadBaseUrl = Yii::$app->params[ConfigString::PARAMS_TAG_READ_URL];
$js = <<<JS
new Vue({
    el: '.vue-tag-container',
    data: {
        dataList: {
            active: { name: '有源标签检测设备', serverOk: false, deviceOk: false, color: 'red' },
            passive: { name: '无源标签检测设备', serverOk: false, deviceOk: false, color: 'red' },
        },
        activeErrorCount: 0,
    },
    methods: {
        checkTagExists: function() {
            var _this = this;
            tagRead.checkActiveStatus(function() {
                _this.dataList.active.serverOk = true;
                _this.dataList.active.deviceOk = true;
                _this.dataList.active.color = 'green';
                _this.activeErrorCount = 0;
            }, function() {
                _this.activeErrorCount++;
                if (_this.activeErrorCount > 10) {
                    _this.dataList.active.serverOk = true;
                    _this.dataList.active.deviceOk = false;
                    _this.dataList.active.color = 'yellow';
                }
            }, function() {
                _this.activeErrorCount++;
                if (_this.activeErrorCount > 10) {
                    _this.dataList.active.serverOk = false;
                    _this.dataList.active.deviceOk = false;
                    _this.dataList.active.color = 'red';
                }
            });
            console.log(_this.activeErrorCount)
            tagRead.checkPassiveStatus(function() {
                _this.dataList.passive.serverOk = true;
                _this.dataList.passive.deviceOk = true;
                _this.dataList.passive.color = 'green';
            }, function() {
                _this.dataList.passive.serverOk = true;
                _this.dataList.passive.deviceOk = false;
                _this.dataList.passive.color = 'yellow';
            }, function() {
                _this.dataList.passive.serverOk = false;
                _this.dataList.passive.deviceOk = false;
                _this.dataList.passive.color = 'red';
            });
        }
    },
    mounted: function() {
        tagRead.baseUrl = '{$tagReadBaseUrl}'
        this.checkTagExists();
        setInterval(this.checkTagExists, 5000);
    }
});
JS;
$this->registerJs($js);

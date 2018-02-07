<?php
/** @var $this \yii\web\View */

use yii\helpers\Url;

\common\assets\VueAssets::register($this);

?>
    <div class="temperature-container" v-cloak>
        <div v-for="item in dataList" class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-red" :class="{'bg-green': item.is_green}"><i class="fa fa-certificate"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ item.title }}</span>
                    <h5 class="info-box-number text-red" :class="{'text-green': item.is_green}">{{ item.content }}</h5>
                    <small class="text-muted">{{ item.limit }}</small>
                </div>
            </div>
        </div>
    </div>
<?php
$temperatureListUrl = Url::to(['/api/home-temperature-data']);
$js = <<<JS
new Vue({
    el: '.temperature-container',
    data: {
        dataList: []
    },
    methods: {
        getDataList: function() {
            var _this = this;
            $.get('{$temperatureListUrl}', {}, function(data) {
                _this.dataList = data
            });
        }
    },
    mounted: function() {
        this.getDataList();
        setInterval(this.getDataList, 3000);
    }
});
JS;
$this->registerJs($js);
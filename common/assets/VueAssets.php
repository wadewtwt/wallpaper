<?php

namespace common\assets;

use yii\web\AssetBundle;

class VueAssets extends AssetBundle
{
    public $sourcePath = '@common/dist/vue2.5.13';

    public $js = [
        'vue.min.js'
    ];
}
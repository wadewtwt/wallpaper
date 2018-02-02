<?php

namespace backend\assets;

use yii\web\AssetBundle;

class TagReadAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/tag-read.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
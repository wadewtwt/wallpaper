<?php

namespace backend\widgets;

use backend\assets\TagReadAsset;
use common\models\base\ConfigString;
use Yii;
use yii\helpers\Html;
use yii\widgets\InputWidget;

// 读取有源标签和无源标签
class SimpleReadTagInput extends InputWidget
{
    public function run()
    {
        $this->registerAssets();
        Html::addCssClass($this->options, 'form-control');
        $input = $this->renderInputHtml('text');
        $html = <<<HTML
<div class="input-group">
    {$input}
    <span class="input-group-btn">
      <button type="button" class="btn btn-primary read_tag">读取</button>
    </span>
</div>
HTML;
        return $html;
    }

    protected function registerAssets()
    {
        TagReadAsset::register($this->view);

        $baseUrl = Yii::$app->params[ConfigString::PARAMS_TAG_READ_URL];
        $js = <<<JS
          tagRead.baseUrl = '{$baseUrl}';
JS;
        $this->view->registerJs($js);
    }
}
<?php

namespace backend\widgets;

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
      <button type="button" class="btn btn-primary read-tag">读取</button>
    </span>
</div>
HTML;
        return $html;
    }

    protected function registerAssets()
    {
        $js = <<<JS
        $('body').on('click', '.read-tag', function() {
          $(this).parents('.input-group').find('input').val('等待处理');
        });
JS;
        $this->view->registerJs($js);
    }
}
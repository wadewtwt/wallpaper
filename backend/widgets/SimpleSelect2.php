<?php

namespace backend\widgets;

use kartik\select2\Select2;

class SimpleSelect2 extends Select2
{
    public $theme = 'default';

    public $defaultOptions = ['prompt' => '请选择'];

    public $language = 'zh-CN';
}
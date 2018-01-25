<?php

namespace backend\controllers;

use common\models\ApplyOrder;

// 一般是用来使用的借出
class ApplyOrderApplyController extends AbstractApplyOrderController
{
    const APPLY_ORDER_TYPE = ApplyOrder::TYPE_APPLY;
}

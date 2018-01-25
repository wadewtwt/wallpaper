<?php

namespace backend\controllers;

use common\models\ApplyOrder;

// 有用于报废，维修，维护的感觉
class ApplyOrderOutputController extends AbstractApplyOrderController
{
    const APPLY_ORDER_TYPE = ApplyOrder::TYPE_OUTPUT;
}

<?php

namespace backend\controllers;

use common\models\Resource;

class ExpendableDetailOperationController extends AbstractResourceDetailOperationController
{
    const RESOURCE_TYPE = Resource::TYPE_EXPENDABLE;
}
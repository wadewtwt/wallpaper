<?php

namespace backend\controllers;

use common\models\Resource;

class ExpendableDetailController extends AbstractResourceDetailController
{
    const RESOURCE_TYPE = Resource::TYPE_EXPENDABLE;
}
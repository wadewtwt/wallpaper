<?php

namespace backend\controllers;

use common\models\Resource;

class DeviceDetailController extends AbstractResourceDetailController
{
    const RESOURCE_TYPE = Resource::TYPE_DEVICE;
}
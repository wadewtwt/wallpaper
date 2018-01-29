<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\models\ResourceDetailSearch;
use common\models\Resource;
use Yii;

abstract class AbstractResourceDetailController extends AuthWebController
{
    const RESOURCE_TYPE = Resource::TYPE_DEVICE;

    // 列表
    public function actionIndex($resource_id = '')
    {
        $this->rememberUrl();

        $searchModel = new ResourceDetailSearch([
            'type' => static::RESOURCE_TYPE,
            'resource_id' => $resource_id
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@view/resource-detail/index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

}

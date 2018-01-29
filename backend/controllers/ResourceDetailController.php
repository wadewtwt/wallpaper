<?php

namespace backend\controllers;

use backend\models\ResourceDetailSearch;
use Yii;

class ResourceDetailController extends AbstractResourceController
{
    // 列表
    public function actionIndex()
    {
        $this->rememberUrl();

        $searchModel = new ResourceDetailSearch([
            'type' => $this->resourceType,
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@view/resource-detail/index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

}

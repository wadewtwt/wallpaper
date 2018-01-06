<?php

namespace backend\models\form;

use common\models\ApplyOrder;
use common\models\ApplyOrderDetail;
use yii\base\Model;

class ApplyOrderInputForm extends Model
{
    /**
     * @var ApplyOrder
     */
    public $model;
    /**
     * @var ApplyOrderDetail[]
     */
    public $models;

    public function input()
    {

    }
}
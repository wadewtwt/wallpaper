<?php

namespace common\models\base;

use Yii;
use yii\behaviors\AttributesBehavior;

class ActiveRecord extends \kriss\components\ActiveRecord
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $attributes = [];
        $time = time();
        $userId = Yii::$app->has('user') ? Yii::$app->get('user')->id : null;
        if ($this->hasAttribute('created_at')) {
            $attributes['created_at'] = [
                self::EVENT_BEFORE_INSERT => $time,
            ];
        }
        if ($this->hasAttribute('updated_at')) {
            $attributes['updated_at'] = [
                self::EVENT_BEFORE_INSERT => $time,
                self::EVENT_BEFORE_UPDATE => $time,
            ];
        }
        if ($userId !== null && $this->hasAttribute('created_by')) {
            $attributes['created_by'] = [
                self::EVENT_BEFORE_INSERT => $userId,
            ];
        }
        if ($userId !== null && $this->hasAttribute('updated_by')) {
            $attributes['updated_by'] = [
                self::EVENT_BEFORE_INSERT => $userId,
                self::EVENT_BEFORE_UPDATE => $userId,
            ];
        }
        $behaviors['common_attributes'] = [
            'class' => AttributesBehavior::className(),
            'attributes' => $attributes
        ];

        return $behaviors;
    }
}
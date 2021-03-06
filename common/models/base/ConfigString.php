<?php

namespace common\models\base;

use common\components\QiNiu;
use Yii;

class ConfigString
{
    // component
    const COMPONENT_REDIS = 'redis';
    const COMPONENT_CACHE_REDIS = 'cache_redis';
    const COMPONENT_SESSION_REDIS = 'session_redis';
    const COMPONENT_QI_NIU = 'qi_niu';

    // log category
    const CATEGORY_NEED_SOLVED = 'need-solved';

    // params
    const PARAMS_TAG_READ_URL = 'tag_read_url';
    const PARAMS_CLEAR_ALARM = 'clear_alarm_url';

    /**
     * @return null|object|QiNiu
     */
    public static function getQiNiu()
    {
        return Yii::$app->get(static::COMPONENT_QI_NIU);
    }

    /**
     * @return \League\Flysystem\Filesystem
     */
    public static function getDisk()
    {
        return static::getQiNiu()->getDisk();
    }
}
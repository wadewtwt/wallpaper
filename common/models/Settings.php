<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 */
class Settings extends \common\models\base\ActiveRecord
{
    const KEY_RK_OPTIONS = 'rk_options'; // 入库选项
    const KEY_CK_OPTIONS = 'ck_options'; // 出库选项
    const KEY_SL_OPTIONS = 'sl_options'; // 申领选项
    const KEY_GH_OPTIONS = 'gh_options'; // 归还选项

    const CACHE_PREFIX = 'settings_';
    const CACHE_TIME = 86400; // 24*3600

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value'], 'required'],
            [['value'], 'string'],
            [['key'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => '键',
            'value' => '值',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        // 保存后重置缓存
        $cache = Yii::$app->cache;
        $cache->set(self::CACHE_PREFIX . $this->key, $this->value);
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * 获取设置的值
     * @param $key
     * @param bool $enableCache
     * @return mixed|string
     */
    public static function getValue($key, $enableCache = true)
    {
        $cache = Yii::$app->cache;
        if ($enableCache) {
            $cacheValue = $cache->get(self::CACHE_PREFIX . $key);
            if ($cacheValue) {
                return $cacheValue;
            }
        }
        $model = self::find()->where(['key' => $key])->one();
        $value = $model->value;
        if ($enableCache) {
            $cache->set(self::CACHE_PREFIX . $key, $value);
        }
        return $value;
    }

    /**
     * 获取 label
     * @param $key
     * @return mixed|string
     */
    public static function getLabel($key)
    {
        $labels = [
            static::KEY_RK_OPTIONS => '入库选项',
            static::KEY_CK_OPTIONS => '出库选项',
            static::KEY_SL_OPTIONS => '申领选项',
            static::KEY_GH_OPTIONS => '归还选项',
        ];
        return $labels[$key];
    }

    /**
     * @param $key
     * @return array
     */
    public static function getValueOptionsForArr($key)
    {
        $value = static::getValue($key);
        $arr = explode("\n", $value);
        return array_combine($arr, $arr);
    }
}

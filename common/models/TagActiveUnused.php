<?php

namespace common\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tag_active_unused".
 *
 * @property integer $id
 * @property string $tag_active
 */
class TagActiveUnused extends \common\models\base\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag_active_unused';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_active'], 'required'],
            [['tag_active'], 'string', 'max' => 255],
            [['tag_active'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tag_active' => '有源标签',
        ];
    }

    /**
     * @return array
     */
    public static function findAllList()
    {
        $models = static::find()->select(['tag_active'])->all();
        return ArrayHelper::map($models, 'tag_active', 'tag_active');
    }
}

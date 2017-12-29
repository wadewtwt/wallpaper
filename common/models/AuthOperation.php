<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "auth_operation".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 */
class AuthOperation extends \common\models\base\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_operation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => '父权限',
            'name' => '权限操作名称',
        ];
    }
}

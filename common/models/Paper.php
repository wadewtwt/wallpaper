<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "paper".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property integer $type
 * @property integer $kind
 * @property string $introduction
 * @property integer $praise
 * @property integer $view
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Paper extends \common\models\base\ActiveRecord
{
    const STATUS_NORMAL = 0;
    const STATUS_DELETED = 99;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paper';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'url'], 'required'],
            [['url'], 'string'],
            [['type', 'kind', 'praise', 'view', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['title', 'introduction'], 'string', 'max' => 255],
            [['praise','view'],'default','value'=>0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'url' => '图片地址',
            'type' => '大类型',
            'kind' => '中类型',
            'introduction' => '简介',
            'praise' => '点赞数',
            'view' => '浏览量',
            'status' => '状态',
            'created_at' => '创建时间',
            'created_by' => '创建人',
            'updated_at' => '修改时间',
            'updated_by' => '修改人',
        ];
    }

    public function findAllKinds(){

    }
}

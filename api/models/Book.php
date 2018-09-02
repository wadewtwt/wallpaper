<?php
namespace api\models;

use common\models\base\ActiveRecord;

class Book extends ActiveRecord{
    public static function tableName()
    {
        return 'book';
    }
}
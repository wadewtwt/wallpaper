<?php
use common\models\base\ConfigString;

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.0.250;dbname=kucun_zhuangbei',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 60,
            'schemaCacheExclude' => [],
            'schemaCache' => 'cache',
            'queryCache' => 'cache',
        ],
    ],
    'params' => [
        ConfigString::PARAMS_TAG_READ_URL => 'http://127.0.0.1:10888',
    ]
];

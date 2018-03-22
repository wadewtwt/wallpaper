<?php
use common\models\base\ConfigString;

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=kucun_zhuangbei',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 600,
            'schemaCacheExclude' => [],
            'schemaCache' => 'cache',
            'queryCache' => 'cache',
        ],
    ],
    'params' => [
        ConfigString::PARAMS_TAG_READ_URL => 'http://127.0.0.1:10888', // 有源无缘请求地址
        ConfigString::PARAMS_CLEAR_ALARM => 'http://127.0.0.1', // 消音点击后的get请求地址
    ]
];

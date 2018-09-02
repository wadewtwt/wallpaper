<?php
use common\models\base\ConfigString;
use yii\rest\UrlRule;
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    // 网站维护，打开以下注释
    //'catchAll' => ['site/offline'],
    'components' => [
//        'request' => [
//            'csrfParam' => '_csrf-frontend',
//        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
//        'session' => [
//            'name' => 'front-session',
//            'class' => 'yii\redis\Session',
//            'redis' => ConfigString::COMPONENT_SESSION_REDIS,
//            'keyPrefix' => 'app_fs_',
//            'timeout' => 3 * 3600
//        ],
        'log' => [
            //'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [],
        ],
//        'errorHandler' => [
//            'errorAction' => 'site/error',
//        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                /** @var \yii\web\Response $response */
                $response = $event->sender;
                if ($response->data !== null && is_array($response->data)) {
                    $response->data = array_merge([
                        'status' => $response->statusCode,
                        'message' => $response->statusText,
                    ], $response->data);
                    $response->statusCode = 200;
                }
            },
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'book',
                    'extraPatterns'=>[
                        'GET send-email'=>'send-email'
                    ],
                ]
            ],
        ],
    ],
    'params' => $params,
];

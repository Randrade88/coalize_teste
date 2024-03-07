<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
          'cookieValidationKey' => '07f76c33d1e9a11acdaf08e5bbd6694bbb73378d7cc3d40e537b02d72257250b',
          'enableCookieValidation' => false,
          'enableCsrfValidation' => false,  
          'parsers' => [
              'application/json' => 'yii\web\JsonParser',
              'multipart/form-data' => 'yii\web\MultipartFormDataParser',
            ]
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'format' => yii\web\Response::FORMAT_JSON,
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            // 'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                  'class' => 'yii\log\FileTarget',
                  'levels' => ['error', 'warning', 'info', 'trace'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
          'enablePrettyUrl' => true,
          'enableStrictParsing' => true,
          'showScriptName' => false,
          'rules' => [
            'POST /auth/login' => 'auth/login',
            'POST /auth/logout' => 'auth/logout',
            'POST /user/create' => 'user/create',
            'GET /user' => 'user/indexs',
            'PUT /user/edit/<id:\d+>' => 'user/edits',
            'DELETE /user/delete/<id:\d+>' => 'user/deletes',
            'POST /cliente/create' => 'cliente/creates',
            'GET /cliente' => 'cliente/list',
            'GET /cliente/<id:\d+>' => 'cliente/indexs',
            'PUT cliente/edit/<id:\d+>' => 'cliente/edits',
            'DELETE cliente/delete/<id:\d+>' => 'cliente/deletes',
            'GET /produto' => 'produto/indexs',
            'POST /produto/create' => 'produto/creates',
            'PUT /produto/edit/<id:\d+>' => 'produto/edits',
            'DELETE /produto/delete/<id:\d+>' => 'produto/deletes',
            'GET /produto/search-by-cliente/<cliente_id:\d+>' => 'produto/search-by-cliente'
          ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,

];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['172.20.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;

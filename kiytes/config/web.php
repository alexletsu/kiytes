<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'kiytes',
    'name' => 'kiytes',
    
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    
    'defaultRoute' => 'site/home',
    'homeUrl' => 'http://localhost/', 
    'components' => [
        'urlManager' => [
            'showScriptName' => false,   // Disable index.php
            'enablePrettyUrl' => true,   // Disable r= routes
            //'enableStrictParsing' => true,
            'rules' => array(
                '<action:[\w-]+>' => 'site/<action>',
                '<action:[\w-]+>/<id:\w+>' => 'site/<action>',
                '<controller:\w+>/<action:[\w-]+>/<id:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:[\w-]+>' => '<controller>/<action>',
            ),
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js'=>[]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js'=>[]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => []
                ]
            ]
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'APWNECP(cn7pa9w8x&P(*X&JE*!^X(TBWQOIXTBIsuaytxebia',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\UserIdent',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;

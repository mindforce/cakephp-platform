<?php
/**
* Licensed under The MIT License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) Mindforce Team (http://mindforce.me)
* @link          http://mindforce.me Platform CakePHP 3 Plugin
* @since         0.0.1
* @license       http://www.opensource.org/licenses/mit-license.php MIT License
*/

$config = [
    [
        'path' => 'debug',
        'title' => 'Debug mode',
        'description' => 'Choose debug level for your site (default: File). If your site is ready for visitors, please choose <strong>Production</strong>.  <strong>Debug</strong> recommended if you want to track errors.',
        'default' => '1',
        'options' => [
            "type" => "radio",
            "options" => [
                "0" => "Production",
                "1" => "Debug"
            ]
        ],
    ],
    [
        'path' => 'App.status',
        'title' => 'Enabled for visitors',
        'default' => '1',
        'options' => [
            "type" => "radio",
            "options" => [
                "0" => "Offline",
                "1" => "Online"
            ]
        ],
    ],
    [
        'path' => 'App.timezone',
        'title' => 'Site timezone',
        'description' => 'UTC is default timezone',
        'default' => 'UTC',
        'cell' => 'Platform.TimeZones',
        'options' => [
            "type" => "select",
            "options" => ['UTC' => 'UTC']
        ],
    ],
    [
        'path' => 'Session.defaults',
        'title' => 'Session engine',
        'description' => 'Choose engine for session storage. Database is prefered for small sites. PHP for big sites under heavy traffik',
        'default' => 'php',
        'options' => [
            "type" => "radio",
            "options" => [
                "php" => "PHP sessions",
                "database" => "Database"
            ]
        ],
    ],
    [
        'path' => 'Session.timeout',
        'title' => 'Session timeout',
        'description' => 'in minutes',
        'default' => '15',
    ],
    [
        'path' => 'Cache.default.duration',
        'title' => 'Cache lifetime',
        'description' => 'in seconds',
        'default' => '300',
    ],
    [
        'path' => 'Cache.default.className',
        'title' => 'Cache engine',
        'description' => 'Choose engine for cache storage (default: File). Always choose <strong>File</strong> if you are not sure in other options',
        'cell' => 'Platform.CacheEngine',
        'default' => 'File',
        'options' => [
            "type" => "select",
            "options" => [
                "File" => "File (default)",
                //currently not supported due dependent settings
                //"Apc" => "APC",
                //"Wincache" => "Wincache",
                //"Xcache" => " Xcache",
                //"Memcached" => "Memcached",
                //"Redis" => "Redis"
            ]
        ],
    ],
    [
        'path' => 'Cache.default.probability',
        'title' => 'Cache probability',
        'description' => 'in seconds',
        'default' => '100',
    ],
    [
        'path' => 'Cache.check',
        'title' => 'Static cache',
        'description' => 'Enable full page caching. Usable for huge sites but may cache dynamic blocks',
        'default' => '0',
        'options' => [
            "type" => "radio",
            "options" => [
                "0" => "No",
                "1" => "Yes"
            ]
        ],
    ],
    [
        'path' => 'Email.queue',
        'title' => 'Defer email sending',
        'description' => 'If this option enabled all emails should be sent via cronjob instead direct sending (Cronjob required)',
        'default' => '0',
        'options' => [
            "type" => "radio",
            "options" => [
                "0" => "No",
                "1" => "Yes"
            ]
        ],
    ],
];

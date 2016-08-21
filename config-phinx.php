<?php
return [
    'paths' => [
        'migrations' => 'migrations'
    ],
    'migration_base_class' => '\Tlab\Libraries\Migration',
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'dev',
        'dev' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'pixel',
            'user' => 'root',
            'pass' => 'xampp',
            'port' => '3306'
        ]
    ]
];
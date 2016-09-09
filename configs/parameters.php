<?php
return [
		'logger' => [
				'file' => __DIR__.'/../logs/app.log',
				'mail' => [
						'to_address' => 'webmaster@example.net',
						'from_address' => 'alerts@example.net',
						'subject' => 'App Logs',
					],
		],
		'database' => [
			'driver'    => 'mysql',
			'host'      => 'localhost',
			'dbname'  => 'pixel',
			'username'  => 'root',
			'password'  => 'xampp',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
			]
];

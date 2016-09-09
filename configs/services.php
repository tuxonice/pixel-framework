<?php
// Value objects are used to reference parameters and services in the container
use Tlab\DI\Reference\ParameterReference as PR;
use Tlab\DI\Reference\ServiceReference as SR;

use Monolog\Logger;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;
use Tlab\Libraries\Database;
use Tlab\Libraries\Authentication;

return [
		StreamHandler::class => [
				'class' => StreamHandler::class,
				'arguments' => [
						new PR('logger.file'),
						Logger::DEBUG,
				],
		],
		NativeMailHandler::class => [
				'class' => NativeMailerHandler::class,
				'arguments' => [
						new PR('logger.mail.to_address'),
						new PR('logger.mail.subject'),
						new PR('logger.mail.from_address'),
						Logger::ERROR,
				],
		],
		Database::class => [
		        'class' => Database::class,
		        'arguments' => [
			            new PR('database')
				],
		],
		Authentication::class => [
				'class' => Authentication::class,
				'arguments' => [
					new SR(Database::class),
				],
		],
		LoggerInterface::class => [
				'class' => Logger::class,
				'arguments' => [ 'channel-name' ],
				'calls' => [
						[
								'method' => 'pushHandler',
								'arguments' => [
										new SR(StreamHandler::class),
								]
						],
						[
								'method' => 'pushHandler',
								'arguments' => [
										new SR(NativeMailHandler::class),
								]
						]
				]
		]
];
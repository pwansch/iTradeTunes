<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overridding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'db' => array(
        'driver'         => 'Pdo',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
	'session' => array(
		'name' => 'ITRADETUNES_SESSION',
	    'remember_me_seconds' => 1209600,
	),		
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter'
                    => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
	'mail_config' => array(
		'sender' => array(
			'name' => 'iTradeTunes',
			'email' => 'noreply@itradetunes.com'
		),
		'retries' => 5,
		'prune' => 30,
	),
	'level' => '1.0.0.0'
);
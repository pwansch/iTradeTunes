<?php
return array(
    'modules' => array(
    	'AkrabatSession',
        'Application',
    	'Trade', // Application specific module
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './module',
            './vendor',
        ),
    ),
);

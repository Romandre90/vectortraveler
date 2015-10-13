<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Vector Traveler System',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),
    'sourceLanguage'=>'00',
    'language'=>'en',
    // Associates a behavior-class with the onBeginRequest event.
    // By placing this within the primary array, it applies to the application as a whole
    'behaviors' => array(
        'onBeginRequest' => array(
            'class' => 'application.components.behaviors.BeginRequest'
        ),
    ),
	
	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'session'=> array(
					'autoStart'=> false,
					//'timeout'=>10,
				),
		
		/*
		'session'=>array(
		    'autoStart'=> false,
			'sessionName' => 'Site Access',
		    'cookieMode' => 'none',
		    'savePath' => 'temp/',
		),
		'ldap'=> array(
			'class'=>'application.extensions.adLDAP.YiiLDAP',
			'options'=> array(
							'ad_port'=>389,
							'domain_controllers' => array('CERN'),
							'account_suffix'=>'@domain_name',
							'base_dn'=> NULL,
							'admin_username'=>'iasensi',
							),
			),*/
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=dbod-vectordb.cern.ch;dbname=vector_new;port=5512',
            'username' => 'admin',
            'password' => 'W41MmVtom52lal',
            'charset' => 'utf8',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
				
				'adminEmail'=>'webmaster@example.com',
                'languages'=>array('fr'=>'Français', 'en'=>'English'),
                'dfs'=>'/afs/cern.ch/work/i/iasensi/www/vector_new/files',
	),
);
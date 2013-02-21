<?php
// module/Trade/conﬁg/module.conﬁg.php:
return array(
        'session_config' => array(
        		'expire_seconds' => 86400,
                'container_name' => 'System_Auth',
        ),
		'controller_plugins' => array(
				'invokables' => array(
						'AuthorizationPlugin' => 'Trade\Controller\Plugin\AuthorizationPlugin',
				)
		),
		'controllers' => array(
				'invokables' => array(
						'Trade\Controller\Album' => 'Trade\Controller\AlbumController',
						'Trade\Controller\Prune' => 'Trade\Controller\PruneController',
						'Trade\Controller\Member' => 'Trade\Controller\MemberController',
				),
		),

		// Routes for the trade module
		'router' => array(
				'routes' => array(
						'album' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/album[/:action][/:id]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id'     => '[0-9]+',
										),
										'defaults' => array(
												'controller' => 'Trade\Controller\Album',
												'action'     => 'index',
										),
								),
						),
						'member' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/member[/:action]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
										),
										'defaults' => array(
												'controller' => 'Trade\Controller\Member',
												'action'     => 'view',
										),
								),
						),
				),
		),
		
		'navigation' => array(
				// the DefaultNavigationFactory uses 'default' as the sitemap key
				'default' => array(
						'album' => array(
								'label' => 'Album',
								'controller' => 'album',
								'action' => 'index',
								'resource' => 'Trade\Controller\Album',
								'privilege' => 'index',
								'pages' => array(
										'Add' => array(
												'label' => 'Add',
												'controller' => 'album',
												'action' => 'add',
												'resource' => 'Trade\Controller\Album',
												'privilege' => 'add',
										),
								),
						),
				),
		),		
		
		// Console routes for the trade module
		'console' => array(
				'router' => array(
						'routes' => array(
								'prune-log' => array(
										'options' => array(
												'route'    => 'prune log [--verbose|-v]',
												'defaults' => array(
														'controller' => 'Trade\Controller\Prune',
														'action'     => 'pruneLog'
												)
										)
								)
						)
				)
		),		

		'view_manager' => array(
			'template_map' => array(
				'trade/layout'           => __DIR__ . '/../view/layout/layout.phtml',
				),
				'template_path_stack' => array(
						'trade' => __DIR__ . '/../view',
				),
		),
);
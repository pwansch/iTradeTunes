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
								'type'    => 'literal',
								'options' => array(
										'route'    => '/album',
										'defaults' => array(
												'controller' => 'Trade\Controller\Album',
												'action' => 'index',
										),
								),
								'may_terminate' => true,
								'child_routes' => array(
										'add' => array(
												'type' => 'literal',
												'options' => array(
														'route' => '/add',
														'defaults' => array(
																'action' => 'add',
														),
												),
										),
										'delete' => array(
												'type' => 'segment',
												'options' => array(
														'route' => '/delete[/:id]',
														'constraints' => array(
																'id' => '[0-9]+',
														),
														'defaults' => array(
																'action' => 'delete',
														),
												),
										),
										'edit' => array(
												'type' => 'segment',
												'options' => array(
														'route' => '/edit[/:id]',
														'constraints' => array(
																'id' => '[0-9]+',
														),
														'defaults' => array(
																'action' => 'edit',
														),
												),
										),
										'page' => array(
												'type' => 'segment',
												'options' => array(
														'route' => '/page[/:id]',
														'constraints' => array(
																'id' => '[0-9]+',
														),
														'defaults' => array(
																'action' => 'index',
														),
												),
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
								'uri' => '/album/index',
								'resource' => 'Trade\Controller\Album',
								'privilege' => 'index',
								'pages' => array(
										'add' => array(
												'label' => 'Add',
												'uri' => '/album/add',
												'resource' => 'Trade\Controller\Album',
												'privilege' => 'add',
										),
								),
						),
						'member' => array(
								'label' => '<identity>',
								'uri' => '/member/view',
								'resource' => 'Trade\Controller\Member',
								'privilege' => 'view',
								'class' => 'pull-right',
								'pages' => array(
										'logout' => array(
												'label' => 'Log Out',
												'uri' => '/member/logout',
												'resource' => 'Trade\Controller\Member',
												'privilege' => 'logout',
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
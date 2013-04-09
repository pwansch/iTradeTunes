<?php

namespace Trade;
use Trade\Model\AlbumTable;
use Trade\Model\Album;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Session\Container;
use Zend\Http\Response;

class Module implements ConsoleUsageProviderInterface
{
	// List all REST routes here explicitely
	protected $REST_ROUTES = array('album-rest');
	
	public function onBootstrap(MvcEvent $e)
	{
		$eventManager = $e->getApplication()->getEventManager();
		$eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'registerJsonStrategy'), 100);
		$eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'checkAndSetAcl'), 200);
		$eventManager->attach(array(MvcEvent::EVENT_DISPATCH, MvcEvent::EVENT_DISPATCH_ERROR), array($this, 'setLayout'), 100);
		
		// Start the session and regenerate the session id to protect from replays
		$sessionManager = Container::getDefaultManager();
		$sessionManager->start();
		$sessionManager->regenerateId(true);
	}

	function setLayout(MvcEvent $e)
	{
		// If a member has been authenticated, set the layout for this module
		$auth = $e->getApplication()->getServiceManager()->get('auth');
		if ($auth->hasIdentity()) {
			$vm = $e->getViewModel();
			$vm->setTemplate('trade/layout');
		}
	}
	
	public function checkAndSetAcl(MvcEvent $e)
	{
		$application = $e->getApplication();
		$sm = $application->getServiceManager();

		// Check if we have a rest route
		$matches = $e->getRouteMatch();
		if(in_array($matches->getMatchedRouteName(), $this->REST_ROUTES))
		{
			return;
		}
		
		// Get the user, controller and action for the request
		$controller = $matches->getParam('controller');
		$action = $matches->getParam('action');
		$auth = $sm->get('auth');
		$session = $sm->get('session');
		$role = 'anonymous';
		if ($auth->hasIdentity()) {
			// Identity exists, so get the role
			$role = $session->role;
		}

		// If the request is not authorized, redirect to the login view
		$authorization = $sm->get('ControllerPluginManager')->get('AuthorizationPlugin');
		if (!$authorization->isAuthorized($role, $controller, $action)) {
			// Set a warning message
			$flashMessenger = $sm->get('ControllerPluginManager')->get('flashmessenger');
			$translator = $sm->get('translator');
			$flashMessenger->addErrorMessage($translator->translate('You are not authorized to access that page. Log in.'));				
			
			// Assemble the url
			$router = $e->getRouter();
			$url = $router->assemble(array(), array('name' => 'home'));

			// Populate and return the response
			$response = $e->getResponse();
			$response->setStatusCode(Response::STATUS_CODE_302);
			$response->getHeaders()->addHeaderLine('Location', $url);
			$e->stopPropagation();
			return $response;
		} 
		
		// If the request is authorized, set the acl in naviation
		$acl = $authorization->getAcl();
		\Zend\View\Helper\Navigation::setDefaultAcl($acl);
		\Zend\View\Helper\Navigation::setDefaultRole($role);
	}	
	
	public function registerJsonStrategy(MvcEvent $e)
	{
		// Get the route
		$matches = $e->getRouteMatch();
		$routeName = $matches->getMatchedRouteName();
		
		if(in_array($routeName, $this->REST_ROUTES))
		{
			// Get the service locator
			$sm = $e->getApplication()->getServiceManager();
				
		    // Set the JSON strategy
		    $view = $sm->get('Zend\View\View');
		    $jsonStrategy = $sm->get('ViewJsonStrategy');
					
		    // Attach strategy, which is a listener aggregate, at high priority
		    $view->getEventManager()->attach($jsonStrategy, 100);
		}
	}
	
	public function getAutoloaderConfig()
	{
		return array(
				'Zend\Loader\ClassMapAutoloader' => array(
						__DIR__ . '/autoload_classmap.php',
				),
				'Zend\Loader\StandardAutoloader' => array(
						'namespaces' => array(
								__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
						),
				),
		);
	}

	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}
	
	public function getServiceConfig()
	{ 
		return array(				
				'factories' => array(
						'session' => function($sm) {
							$sessionContainer = new Container();
							return $sessionContainer;
						},
						'auth' => function($sm) {
							$auth = new AuthenticationService();
                            $config = $sm->get('config');
                           	$sessionContainer = new Container($config['session_config']['container_name']);
                           	$sessionContainer->setExpirationSeconds($config['session_config']['expire_seconds']);
                            $auth->setStorage(new \Zend\Authentication\Storage\Session($config['session_config']['container_name']));
							return $auth;
						},
						'authAdapter' => function($sm) {
							$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
							$authAdapter = new AuthAdapter($dbAdapter);
							$authAdapter->setTableName('member')
							            ->setIdentityColumn('email_address')
							            ->setCredentialColumn('password_encrypted')
							            ->setCredentialTreatment('MD5(?) AND status = 1');							
							return $authAdapter;
						},
						'Trade\Model\AlbumTable' =>  function($sm) {
							$tableGateway = $sm->get('AlbumTableGateway');
							$table = new AlbumTable($tableGateway);
							return $table;
						},
						'AlbumTableGateway' => function ($sm) {
							$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype(new Album());
							return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);
						},	
				),
		); 
	}

	public function getConsoleUsage(Console $console){
		return array(
				// Describe available commands
				'prune log [--verbose|-v]' => 'Prune log',
	
				// Describe expected parameters
				array( '--verbose|-v',     '(optional) turn on verbose mode'        ),
		);
	}	
}

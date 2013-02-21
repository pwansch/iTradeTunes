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
	public function onBootstrap(MvcEvent $e)
	{
		$eventManager = $e->getApplication()->getEventManager();
		$eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'checkAndSetAcl'), 200);
		$eventManager->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {
			// If a member has been authenticated, set the layout for this module
			$auth = $e->getApplication()->getServiceManager()->get('auth');
			if ($auth->hasIdentity()) {
				$controller = $e->getTarget();
				$controller->layout('trade/layout');
			}			
		}, 100);		
	}
	
	public function checkAndSetAcl(MvcEvent $e)
	{
		$application = $e->getApplication();
		$sm = $application->getServiceManager();

		// Get the user, controller and action for the request
		$matches = $e->getRouteMatch();
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
			$flashMessenger->addMessage($translator->translate('You are not authorized to access that page. Log in.'));				
			
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
				'services' => array(
						 'session' => new Container(),
						),				
				'factories' => array(
						'auth' => function($sm) {
							$auth = new AuthenticationService();
                            $config = $sm->get('config');
                            if (isset($config['session_config']['container_name'])) {
                            	$sessionContainer = new Container($config['session_config']['container_name']);
                            } else {
                            	// Use a default session container name
                            	$sessionContainer = new Container('System_Auth');
                            }
                            
                            if (isset($config['session_config']['expire_seconds'])) {
                            	$sessionContainer->setExpirationSeconds($config['session_config']['expire_seconds']);
                            } else {
                            	// Use a default timeout of 10 minutes
                            	$sessionContainer->setExpirationSeconds(600);
                            }                            
                            $auth->setStorage(new \Zend\Authentication\Storage\Session());
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

<?php

namespace Trade;
use Trade\Model\AlbumTable;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;

class Module implements ConsoleUsageProviderInterface
{
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
						 'auth' => new AuthenticationService(),				
						),				
				'factories' => array(
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
							$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
							$table     = new AlbumTable($dbAdapter);
							return $table;
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

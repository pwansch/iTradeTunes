<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Utilities\Logger;
use Application\Utilities\Mail;
use Zend\Mvc\ModuleRouteListener;
use Application\View\Helper\AbsoluteUrl;
use Application\View\Helper\UrlLevelPath;

class Module
{
    public function onBootstrap($e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getViewHelperConfig()
    {
    	return array(
    			'factories' => array(
    					// The array key here is the name you will call the view helper by in your view scripts
    					'absoluteUrl' => function($sm) {
    						$locator = $sm->getServiceLocator(); // $sm is the view helper manager, so we need to fetch the main service manager
    						return new AbsoluteUrl($locator->get('Request'));
    					},
    					
    					'urlLevelPath' => function($sm) {
    						$locator = $sm->getServiceLocator();
    						return new UrlLevelPath($locator);
    					},
    			),
    			'invokables' => array(
    					// View helpers
    					'flashMessages' => 'Application\View\Helper\FlashMessages',
    			),    			
    	);
    } 
    
    public function getServiceConfig()
    {
    	return array(
    			'factories' => array(
    					'logger' => function($sm) {
    						$logger = new Logger($sm);
    						return $logger;
    					},
    					'mail' => function($sm) {
    						$mail = new Mail($sm);
    						return $mail;
    					},
    			),
    	);
    }
}

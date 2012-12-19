<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	//$session->offsetExists('email')
    	
    	//$email = $session->offsetGet('email');
    	
    	//$session->offsetSet('email', 'pwansch@me.com');

    	
    	
    	//var_dump($session->offsetGet('email'));
        return new ViewModel();
    }
}
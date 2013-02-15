<?php
namespace Application\View\Helper;
 
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;
 
class FlashMessages extends AbstractHelper implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
    	$this->serviceLocator = $serviceLocator;
    }
    
    public function getServiceLocator() {
    	return $this->serviceLocator; 
    }
    	     
    public function __invoke()
    {
    	// Get the messages from the service flash messenger
    	$flashMessenger = $this->getServiceLocator()->get('flashmessenger');
    	$messages = $flashMessenger->getMessages();
    	
    	// Check for any recently added messages
    	if ($flashMessenger->hasCurrentMessages())
    	{
    		$messages += $flashMessenger->getCurrentMessages();
    		$flashMessenger->clearCurrentMessages();
    	}
    	
    	return $messages;
    }
}
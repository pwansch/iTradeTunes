<?php
namespace Application\View\Helper;
 
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;
 
class FlashMessages extends AbstractHelper implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    protected $flashMessenger;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
    	$this->serviceLocator = $serviceLocator;
    }
    
    public function getServiceLocator() {
    	return $this->serviceLocator; 
    }

    public function errorMessages()
    {
    	$errorMessages = $this->getFlashMessenger()->getErrorMessages();
    	
    	// Check for any recently added messages
    	if ($this->getFlashMessenger()->hasCurrentErrorMessages())
    	{
    		$errorMessages += $this->getFlashMessenger()->getCurrentErrorMessages();
    		$this->getFlashMessenger()->clearCurrentErrorMessages();
    	}
    	
    	return $errorMessages;
    }
    
    public function infoMessages()
    {
    	$infoMessages = $this->getFlashMessenger()->getInfoMessages();
    	
    	// Check for any recently added messages
    	if ($this->getFlashMessenger()->hasCurrentInfoMessages())
    	{
    		$infoMessages += $this->getFlashMessenger()->getCurrentInfoMessages();
    		$this->getFlashMessenger()->clearCurrentInfoMessages();
    	}
    	
    	return $infoMessages;
    }
    
    public function successMessages()
    {
    	$successMessages = $this->getFlashMessenger()->getSuccessMessages();
    	
    	// Check for any recently added messages
    	if ($this->getFlashMessenger()->hasCurrentSuccessMessages())
    	{
    		$successMessages += $this->getFlashMessenger()->getCurrentSuccessMessages();
    		$this->getFlashMessenger()->clearCurrentSuccessMessages();
    	}
    	
    	return $successMessages;
    }
    
    public function messages()
    {
    	$messages = $this->getFlashMessenger()->getMessages();
    	 
    	// Check for any recently added messages
    	if ($this->getFlashMessenger()->hasCurrentMessages())
    	{
    		$messages += $this->getFlashMessenger()->getCurrentMessages();
    		$this->getFlashMessenger()->clearCurrentMessages();
    	}
    	 
    	return $messages;
    }
    
    public function getFlashMessenger()
    {
    	if (!$this->flashMessenger) {
    		$this->flashMessenger = $this->getServiceLocator()->get('flashmessenger');
    	}
    	return $this->flashMessenger;
    }
}
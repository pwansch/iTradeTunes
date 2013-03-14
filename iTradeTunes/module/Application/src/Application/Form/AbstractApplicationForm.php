<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractApplicationForm extends Form implements ServiceLocatorAwareInterface
{
	protected $sm;
	protected $translator;
	protected $session;
	
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->sm = $serviceLocator;
	}
	
	public function getServiceLocator()
	{
		return $this->sm;
	}		
	
	public function translate($message)
	{
		if (!$this->translator) {
			// Retrieve the translator from the main service manager
			$this->translator = $this->getServiceLocator()->getServiceLocator()->get('translator');
		}
		return $this->translator->translate($message);
	}
	
	public function getSession()
	{
		if (!$this->session) {
			$sm = $this->getServiceLocator();
			$this->session = $this->getServiceLocator()->getServiceLocator()->get('session');
		}
		return $this->session;
	}	
}
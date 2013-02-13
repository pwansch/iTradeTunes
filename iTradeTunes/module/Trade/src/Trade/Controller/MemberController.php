<?php

namespace Trade\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\Result;
use Trade\Form\MemberForm;

class MemberController extends AbstractActionController
{
	protected $auth;
	protected $authAdapter;
	protected $translator;
	protected $session;	
		
    public function loginAction()
    {
    	// Try to authenticate the member

    	// Clear the identity first
    	$this->getAuth()->clearIdentity();

    	// Attempt authentication and check result
    	$this->getAuthAdapter()->setIdentity('sfenwick@itradetunes.com')
                               ->setCredential('password1');   
    	$result = $this->getAuth()->authenticate($this->getAuthAdapter());
    	
    	if (!$result->isValid())
    	{
    		// Check the result to provide a useful message
    		switch ($result->getCode())
    		{
    			case Result::FAILURE_IDENTITY_NOT_FOUND:
    				$this->flashMessenger()->addMessage($this->getTranslator()->translate('Invalid email address or password.'));
    				break;
    	
    			case Result::FAILURE_CREDENTIAL_INVALID:
    			default:
					$this->flashMessenger()->addMessage($this->getTranslator()->translate('Invalid email address or password.'));
    				break;
    		}

    		return $this->redirect()->toRoute('application');
    	}

    	// Get the result row, set the role and redirect to list of albums
    	$columnsToOmit = array('password_encrypted');
    	$resultRow = $this->getAuthAdapter()->getResultRowObject(null, $columnsToOmit);
    	$this->getSession()->role = 'member';
    	return $this->redirect()->toRoute('album');
    }

    public function joinAction()
    {
    	$form = new MemberForm();
    	$form->get('submit')->setValue('Join');
    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		//$album = new Album();
    		//$form->setInputFilter($album->getInputFilter());
    		//$form->setData($request->getPost());
    	
    		//if ($form->isValid()) {
    		//	$album->exchangeArray($form->getData());
    		//	$this->getAlbumTable()->saveAlbum($album);
    	
    			// Redirect to list of albums
    		//	return $this->redirect()->toRoute('album');
    		//}
    	}
    	return array('form' => $form);    	
    }
    
    public function viewAction()
    {
    }    

    public function getAuth()
    {
    	if (!$this->auth) {
    		$sm = $this->getServiceLocator();
    		$this->auth = $sm->get('auth');
    	}
    	return $this->auth;
    }
    
    public function getAuthAdapter()
    {
    	if (!$this->authAdapter) {
    		$sm = $this->getServiceLocator();
    		$this->authAdapter = $sm->get('authAdapter');
    	}
    	return $this->authAdapter;
    }
    
    public function getTranslator()
    {
    	if (!$this->translator) {
    		$sm = $this->getServiceLocator();
    		$this->translator = $sm->get('translator');
    	}
    	return $this->translator;
    }    
    
    public function getSession()
    {
    	if (!$this->session) {
    		$sm = $this->getServiceLocator();
    		$this->session = $sm->get('session');
    	}
    	return $this->session;
    }
}
<?php

namespace Trade\Controller;

use Application\Controller\AbstractApplicationController;
use Zend\Authentication\Result;
use Trade\Form\MemberForm;

class MemberController extends AbstractApplicationController
{
	protected $auth;
	protected $authAdapter;
		
    public function loginAction()
    {
    	$request = $this->getRequest(); 
    	if ($request->isPost()) {
    		// Get the login form parameters which are directly coded into the layout
    		$email = $request->getPost('email', '');
    		$password = $request->getPost('password', '');
    		$rememberMe = (int) $request->getPost('rememberMe', 0);

    		// Clear the identity first
    		$this->getAuth()->clearIdentity();
    		
    		// Attempt authentication and check result
    		$this->getAuthAdapter()->setIdentity($email)
    							   ->setCredential($password);
    		$result = $this->getAuth()->authenticate($this->getAuthAdapter());
    		 
    		if (!$result->isValid())
    		{
    			// Check the result to provide a useful message
    			switch ($result->getCode())
    			{
    				case Result::FAILURE_IDENTITY_NOT_FOUND:
    					$this->flashMessenger()->addErrorMessage($this->getTranslator()->translate('Invalid email address or password.'));
    					break;
    					 
    				case Result::FAILURE_CREDENTIAL_INVALID:
    				default:
    					$this->flashMessenger()->addErrorMessage($this->getTranslator()->translate('Invalid email address or password.'));
    					break;
    			}

    			return $this->redirect()->toRoute('home');
    		}
    		
    		// Get the result row, set the role and redirect to list of albums
    		$columnsToOmit = array('password_encrypted');
    		$resultRow = $this->getAuthAdapter()->getResultRowObject(null, $columnsToOmit);
    		$this->getSession()->role = 'member';
    		if ($rememberMe == 1)
    		{
    		    $this->getSession()->rememberMe = true;
    		}
    		return $this->redirect()->toRoute('album');
    	}
    	
    	// For a GET request, redirect to the home page
    	return $this->redirect()->toRoute('home');
    }
    
    public function logoutAction()
    {
    	// Clear the identity, destroy the session and redirect
    	$this->getAuth()->clearIdentity();
    	$this->getSession()->getManager()->destroy();
    	return $this->redirect()->toRoute('home');
    }

    public function joinAction()
    {
    	// Create and initialize the member form for join
    	$form = $this->getFormManager()->get('Trade\Form\MemberForm');
    	$form->get('submit')->setValue($this->getTranslator()->translate('Join'));

    	// Process the request
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$member = new Member();
    		$form->setInputFilter($member->getInputFilter());
    		$form->setData($request->getPost());
    	
    		if ($form->isValid()) {
    			// Create the new member in the database
    		    $member->exchangeArray($form->getData());
    		    $this->beginTransaction();
    			// $this->getAlbumTable()->saveAlbum($album);
    		    $this->commitTransaction();
    			
    		    // Redirect to to the home page
    		    return $this->redirect()->toRoute('home');
    		}
    	}
    	
    	// Return the form to the view
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
}
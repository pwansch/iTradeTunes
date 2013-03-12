<?php
namespace Application\Form;

use Zend\Form\Form;

class LoginForm extends AbstractApplicationForm
{
	public function __construct($name = null)
	{
		parent::__construct('login');
		$this->setAttribute('method', 'post');
		$this->add(array(
				'name' => 'email',
				'attributes' => array(
						'type'  => 'text',
				),
		));
		$this->add(array(
				'name' => 'password',
				'attributes' => array(
						'type'  => 'password',
				),
		));
		$this->add(array(
				'name' => 'submit',
				'attributes' => array(
						'type'  => 'submit',
						'id' => 'submitbutton',
				),
		));
	}
}
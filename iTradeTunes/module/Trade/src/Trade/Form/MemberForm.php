<?php

namespace Trade\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class MemberForm extends Form
{
	public function __construct($name = null)
	{
		// We ignore the name passed
		parent::__construct('member');
		$this->setAttribute('method', 'post');
		
		$id = new Element\Hidden('id');
		$first_name = new Element\Text('first_name');
		$first_name->setLabel('First Name')
		           ->setAttributes(array('size'  => '32'));
		$last_name = new Element\Text('last_name');		
		$last_name->setLabel('Last Name')
		          ->setAttributes(array('size'  => '32'));
		$email_address = new Element\Email('email_address');
		$email_address->setLabel('Email Address')
		      ->setAttributes(array('size'  => '96'));
		$email_address_private = new Element\Checkbox('email_address_private');
		$email_address_private->setLabel('Email address private');
		$email_address_private->setUseHiddenElement(true);
		$email_address_private->setCheckedValue("1");
		$email_address_private->setUncheckedValue("0");
		$password = new Element\Password('password');
		$password->setLabel('Password')
		         ->setAttributes(array('size'  => '30'));
		$about = new Element\Text('about');
		$about->setLabel('About')
	           ->setAttributes(array('size'  => '255'));
		$interests = new Element\Text('interests');
		$interests->setLabel('Interests')
		          ->setAttributes(array('size'  => '255'));
		$goto = new Element\Text('goto');
		$goto->setLabel('Go To')
		     ->setAttributes(array('size'  => '255'));
		$submit = new Element\Submit('submit');
		$submit->setValue('Join');
		
		// Add elements to form
		$this->add($id)
		     ->add($first_name)
		     ->add($last_name)
		     ->add($email_address)
		     ->add($email_address_private)
		     ->add($password)
		     ->add($about)
		     ->add($interests)
		     ->add($goto)
		     ->add($submit);	

	}
}
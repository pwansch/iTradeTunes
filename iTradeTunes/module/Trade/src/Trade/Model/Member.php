<?php
// module/Trade/src/Trade/Model/Member.php:
namespace Trade\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Member implements InputFilterAwareInterface
{
    public $id;
    public $first_name;
    public $last_name;
    public $email_address;
    public $email_address_private;
    public $password;
    public $about;
    public $interests;    
    
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id']))     ? $data['id']     : null;
        $this->first_name = (isset($data['first_name'])) ? $data['first_name'] : null;
        $this->last_name = (isset($data['last_name'])) ? $data['last_name'] : null;
        $this->email_address = (isset($data['email_address'])) ? $data['email_address'] : null;
        $this->email_address_private = (isset($data['email_address_private'])) ? $data['email_address_private'] : null;
        $this->password = (isset($data['password'])) ? $data['password'] : null;
        $this->about = (isset($data['about'])) ? $data['about'] : null;
        $this->interests = (isset($data['interests'])) ? $data['interests'] : null;
    }
    
    // Add the following method:
    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }    

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'first_name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 2,
                            'max'      => 30,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
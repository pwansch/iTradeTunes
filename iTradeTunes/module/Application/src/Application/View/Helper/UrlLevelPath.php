<?php
namespace Application\View\Helper;
 
use Zend\View\Helper\AbstractHelper;
 
class UrlLevelPath extends AbstractHelper
{
    protected $serviceLocator;
 
    public function __construct($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
 
    public function __invoke($dirName, $fileName)
    {
        return '/' . $dirName . '/' . $this->serviceLocator->get('config')['level'] . '/' . $fileName;
    }
}
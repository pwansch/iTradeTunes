<?php

/**
 * Application logger.
 *
 * @copyright  2009-2011 CommunitySquared Inc.
 * @version    $Id$
 * @since      File available since release 1.0.0.0
 */

namespace Application\Utilities;

final class Logger implements ServiceLocatorAwareInterface
{
	protected $serviceLocator;
    private static $config = null; 
    
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
    	$this->serviceLocator = $serviceLocator;
    }
    
    public function getServiceLocator() {
    	return $this->serviceLocator;
    }
    
    public static function log($message, $priority = Zend_Log::DEBUG, $code = Log::LOG_CODE_DEFAULT) 
    {
        // Get the priority
        $logLevel = intval(Zend_Registry::get('config')->log->level);    	     	    	    	
    	
        // DB Logger
        if (Zend_Registry::isRegistered('dbLogger'))
        {
            $dbLogger = Zend_Registry::get('dbLogger');
        }
        else
        {        
    	    $dbWriter = self::getDBLogWriter($logLevel);
    	    $dbLogger = new Zend_Log($dbWriter);
            Zend_Registry::set('dbLogger', $dbLogger);
        }

        // Set a differently formatted timestamp
        $dbLogger->setEventItem('timestamp', GgvModel::getMySQLDateNowUTC());

        // Remove newline characters from log message
        $newLineChars = array('\n', '\r');      
        $message = str_replace($newLineChars, "", $message );      
              
        // Log the event
        $dbLogger->setEventItem('code', $code);
        if (array_key_exists('REMOTE_ADDR', $_SERVER)) 
        {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }
        else
        {
            $ipAddress = null;
        }
        $dbLogger->setEventItem('ipAddress', $ipAddress);
        
        $memberEmailAddress = null;
        try 
        {
        	$session = Zend_Registry::get('session');
        	if ($session->authEmailAddress)
        	{
        		$memberEmailAddress = $session->authEmailAddress;
        	}
        }
        catch (Zend_Exception $e)
        {
        	// The member email address will be set to null
        }
        $dbLogger->setEventItem('memberEmailAddress', $memberEmailAddress);
        
        // Log the event
        $dbLogger->log($message, $priority);
                
        // FireBug Logger       
        if(Zend_Registry::get('config')->log->firebug)
        {
            if(Zend_Registry::isRegistered('fbLogger'))
            {
                $fbLogger = Zend_Registry::get('fbLogger');
            }
            else
            {
                $fbWriter = new Zend_Log_Writer_Firebug();
                $fbWriter->addFilter(new Zend_Log_Filter_Priority($logLevel));
	            $fbLogger = new Zend_Log($fbWriter);
                Zend_Registry::set('fblogger', $fbLogger);   
            }        	            
            // Log the message 
	        $fbLogger->log($message, $priority);		                     	  
        }        
    }
    
    public static function formatException($exception)
    {
    	$message = 'Message: ' . $exception->getMessage() . ' Stack trace: ' . $exception->getTraceAsString();
    	return $message;
    }
    
    public static function getDBLogWriter($logLevel)
    {       
        $db = Zend_Registry::get('db') ;
        $columnMapping = array(
            'log_timestamp' => 'timestamp', 
            'log_level' => 'priorityName', 
            'log_message' => 'message', 
            'log_code' => 'code',
            'log_ip_address' => 'ipAddress',
            'log_member_email_address' => 'memberEmailAddress'
        );

        $writer = new Zend_Log_Writer_Db($db, 'log', $columnMapping);
        $writer->addFilter(new Zend_Log_Filter_Priority($logLevel));
        return $writer;
    }  
    
    public static function debug($variable, $location)
    {
        // Load the config from the registry
        if (is_null(self::$config))
        {
            self::$config = Zend_Registry::get('config');            
        }
        
        // Add a debug entry
        if (self::$config->log->level == Zend_Log::DEBUG)
        {
            $dump = Zend_Debug::dump($variable, null, false);
            GgvLogger::log($location . ':' . $dump, Zend_Log::DEBUG);    
        }
    }
}
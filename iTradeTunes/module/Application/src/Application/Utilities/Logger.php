<?php

/**
 * Application logger.
 *
 * @copyright  2009-2011 CommunitySquared Inc.
 * @version    $Id$
 * @since      File available since release 1.0.0.0
 */

namespace Application\Utilities;

use Zend\Log\Filter\Priority;
use Zend\Log\Logger as ZendLogger;
use Zend\Log\Writer\Db as DbWriter;
use DateTime;
use DateTimeZone;

final class Logger
{
	const CODE_DEFAULT = 0;
	const CODE_ACCOUNT_LOCKED = 1;
	const CODE_MAIL = 2;
	protected $logger;
	protected $auth;
    
	public function __construct($sm)
	{
		// Get logging configuration
		$config = $sm->get('config');
		if (isset($config['logger_config']['level'])) {
			$level = $config['logger_config']['level'];
		} else {
			$level = ZendLogger::ERR;
		}
		if (isset($config['logger_config']['details'])) {
			$details = $config['logger_config']['details'];
		} else {
			$details = false;
		}
		
		// Get authentication adapter
		$this->auth = $sm->get('auth');

		// Configurate database logging adapter
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$columnMapping = array(
				'timestamp' => 'timestamp',
				'priorityName' => 'level',
				'message' => 'message',
				'extra' => array (
					'code' => 'code',
					'emailAddress' => 'email_address',
					'ipAddress' => 'ip_address',
				),
		);
		$writer = new DbWriter($dbAdapter, 'log', $columnMapping);
		$writer->addFilter(new Priority($level));
		$this->logger = new ZendLogger();
		$this->logger->addWriter($writer);
	}
	    
    protected function getLogger()
    {
    	return $this->logger;
    }
    
    public function log($priority = ZendLogger::DEBUG, $message, $code = Logger::CODE_DEFAULT) 
    {
    	$extra = array();

        // Remove newline characters from log message
        $newLineChars = array('\n', '\r');      
        $message = str_replace($newLineChars, '', $message);      
              
        // Log the event
        if (!is_null($code))
        {
        	$extra['code'] = $code;
        }
        else
        {
        	$extra['code'] = Logger::CODE_DEFAULT;
        }
        
        if ($this->auth->hasIdentity()) {
        	$emailAddress = $this->auth->getIdentity();
        }
        else
        {
        	$emailAddress = '';
        }
        $extra['emailAddress'] = $emailAddress;

        if (array_key_exists('REMOTE_ADDR', $_SERVER)) 
        {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }
        else
        {
            $ipAddress = null;
        }
        $extra['ipAddress'] = $ipAddress;
        
        // Log the message
        $this->getLogger()->log($priority, $message, $extra);
    }
    
    public function formatException($exception)
    {
    	$message = 'Message: ' . $exception->getMessage() . ' Stack trace: ' . $exception->getTraceAsString();
    	return $message;
    }
}
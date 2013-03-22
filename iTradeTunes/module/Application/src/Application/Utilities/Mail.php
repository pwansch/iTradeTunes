<?php

/**
 * Template-based mail.
 *
 * @copyright  2009-2011 CommunitySquared Inc.
 * @version    $Id$
 * @since      File available since release 1.0.0.0
 */

/** 
 * Sample template:
 *
 * Dear {$member_first_name},
 * 
 * We are excited that you are joining the GoGoVerde community as a member.
 * Click on the confirmation link below to finish your registration:
 * {$member_url}
 * 
 * If this email reaches you in error you do not have to do anything. Unconfirmed
 * accounts will be deleted after 24 hours.
 * 
 * Regards,
 * Your team at GoGoVerde.com
 * support@gogoverde.com 
 * 
 * Usage sample:
 * 
 * $ggvMail = new GgvMail('join');
 * $ggvMail->addSubjectArray(
 *             array (
 *                 'member_first_name'=> 'Peter'
 *             ));
 * $ggvMail->addArray(
 *             array (
 *                 'member_first_name'=> 'Peter',
 *                 'member_url'=> 'http//www.gogoverde.com/member/join/...',
 *             ));
 * $ggvMail->queue('pwansch@gogoverde.com', 'Peter Wansch');             
 */

namespace Application\Utilities;

require_once 'library/ggv/GgvMailException.php';
require_once 'application/models/MailTemplate.php';
require_once 'application/models/Notification.php';
require_once 'application/models/Log.php';
require_once 'library/ggv/GgvUtil.php';
require_once 'library/ggv/GgvLogger.php';

final class Mail
{
    const URL_PREFIX = '<';
    const URL_POSTFIX = '>';    
    private $_templateType;
    private $_templateText;
    private $_templateHTML;
    private $_log;
    private $_subject;
    private $_subjectVariables;
    private $_variables;
    private $_variablesHTML;
    private $_mailTemplate;

    public static function setDefaultMailParameters($sendmail = true, $smtpConfig = array())
    {
        if ($sendmail)
        {
            $transport = new Zend_Mail_Transport_Sendmail();
        } 
        else 
        {
            if ($smtpConfig instanceof Zend_Config) 
            {
                $smtpConfig = $smtpConfig->toArray();
            }
            $transport = new Zend_Mail_Transport_Smtp($smtpConfig['server'], $smtpConfig);
        }
        
        Zend_Mail::setDefaultTransport($transport);
    }
    
    public function __construct($sm)
    {
    	
    	
    	
    	
    }    
    
    public function setTemplate($template)
    {   
        $this->_mailTemplate = new MailTemplate();
        
        $templateRow = $this->_mailTemplate->getDefaultTemplateByTitle($template);
        $this->_templateType = $templateRow['mail_template_type'];
        $this->_subject =  $templateRow['mail_template_subject'];
        $this->_templateText = $templateRow['mail_template_text'];
        $this->_templateHTML = $templateRow['mail_template_html'];
        if ($templateRow['mail_template_log'] == MailTemplate::MAIL_TEMPLATE_LOG_YES)
        {
            $this->_log = true;
        }
        else
        {
            $this->_log = false;
        }
        $this->resetSubjectVars();
        $this->resetVars(); 
    }

    public function resetSubjectVars()
    {
        $this->_subjectVariables = array();
    }
    
    public function resetVars()
    {
        $this->_variables = array();
    }

    public function resetVarsHTML()
    {
        $this->_variablesHTML = array();
    }
    
    public function addSubject($var_name, $var_data)
    {
        $this->_subjectVariables[$var_name] = $var_data;
    }
    
    public function add($var_name, $var_data)
    {
    	$this->_variables[$var_name] = $var_data;
    }

    public function addHTML($var_name, $var_data)
    {
    	$this->_variablesHTML[$var_name] = $var_data;
    }
    
    public function addSubjectArray($array)
    {
        if (!is_array($array) )
        {
            return;
        }
        
        foreach ($array as $name => $data)
        {
            $this->addSubject($name, $data);
        }
    }
    
    public function addArray($array)
    {
        if (!is_array($array) )
        {
            return;
        }
        
        foreach ($array as $name => $data)
        {
            $this->add($name, $data);
        }
    }

    public function evaluateSubject()
    {
        $subject = addslashes($this->_subject);       
        
        if (!is_null($this->_subjectVariables))
        {
            foreach ($this->_subjectVariables as $variable => $data)
            {
                $$variable = $data;
            }
        }
        
        eval("\$subject = \"$subject\";");       
        return stripslashes($subject); 
    }
    
    public function evaluate()
    {
        $templateText = addslashes($this->_templateText);       
        
        if (!is_null($this->_variables))
        {
            foreach ($this->_variables as $variable => $data)
            {
                $$variable = $data;
            }
        }
        
        eval("\$templateText = \"$templateText\";");       
        return stripslashes($templateText); 
    }
     
    public function evaluateHTML()
    {
        $templateHTML = addslashes($this->_templateHTML);       

        if (!is_null($this->_variablesHTML))
        {
            foreach ($this->_variablesHTML as $variable => $data)
            {
                $$variable = $data;
            }
        }
        
        eval("\$templateHTML = \"$templateHTML\";");       
        return stripslashes($templateHTML); 
    }
    
    public function queue($toEmail, $toName, $subject = null, $stripMultipleBlankLines = false)
    {
        $notification = new Notification();
        
        if (!is_null($subject))
        {
            $mailSubject = $subject;
        }
        else
        {
            $mailSubject = $this->_subject;
        }
        
        // Evaluate the subject
        $mailSubject = $this->evaluateSubject();
        
        // Evaluate the text body
        $mailBodyText = $this->evaluate();
        if ($stripMultipleBlankLines)
        {
            $mailBodyTextArray = preg_split('/\n/', $mailBodyText);
            $blankSequence = 0;
            $mailBodyText = '';
            foreach ($mailBodyTextArray as $mailBodyTextLine) 
            {
            	if (strlen(trim($mailBodyTextLine)) > 0)
            	{
            		if (strlen($mailBodyText) == 0)
            		{
            			$mailBodyText = $mailBodyTextLine;
            		}
            		else
            		{
           	            $mailBodyText = $mailBodyText . ($blankSequence > 0 ? "\n\n" : "\n") . $mailBodyTextLine;
            			$blankSequence = 0;
            		}
            	}
            	else
            	{
            		$blankSequence++;
            	}
            }             
        }
        
        // Evaluate the HTML body
        $mailBodyHTML = null;
        if ($this->_templateType == MailTemplate::MAIL_TEMPLATE_TYPE_HTML)
        {
            $mailBodyHTML = $this->evaluateHTML();
        }
        
        // Insert notification
        $newRow = $notification->addNotification($toName, $toEmail, $mailSubject, $this->_templateType, $mailBodyText, $mailBodyHTML);

        // Add log record if required
        if ($this->_log)
        {
            GgvLogger::log('Email notification queued to: ' . $toName . ' at: ' . $toEmail . ' for subject: ' . $mailSubject . ' with notification id: ' . $newRow['notification_id'], Zend_Log::NOTICE, Log::LOG_CODE_MAIL);
        }
    }

    public static function checkName($name)
    {
    	if (ctype_alnum($name))
    	{
    		return $name;
    	}
    	else
    	{
            return '';
    	}
    }
    
    /**
     * @throws GgvMailException
     */    
    public static function send($toEmail, $toName, $fromEmail = 'noreply@gogoverde.com', $fromName = 'GoGoVerde', $subject, $bodyText, $type = MailTemplate::MAIL_TEMPLATE_TYPE_HTML, $bodyHTML = null, $attachmentFilename = null, $attachment = null)
    {
        try
        {
            $mail = new Zend_Mail('UTF-8');
            $mail->addTo($toEmail, self::checkName($toName));
            $mail->setFrom($fromEmail, self::checkName($fromName));
            $mail->setBodyText($bodyText);
            if ($type == MailTemplate::MAIL_TEMPLATE_TYPE_HTML)
            {
                $mail->setBodyHTML($bodyHTML);
            }
            $mail->setSubject($subject);
            
            if (!is_null($attachment))
            {
                $at = $mail->createAttachment($attachment);
                $at->type = Zend_Mime::TYPE_OCTETSTREAM;
                $at->disposition = Zend_Mime::DISPOSITION_INLINE;
                $at->encoding = Zend_Mime::ENCODING_BASE64;
                if (!is_null($attachmentFilename))
                {
                    $at->id = $attachmentFilename;
                	$at->filename = $attachmentFilename;   
                }                     
            }
            
            $mail->send();
        }
        catch (Exception $e)
        {             
            $translatedMessage = GgvUtil::translate('Unable to send email.');
            throw new GgvMailException($translatedMessage, 0, $e);
        }
    }
    
    public static function sendSupportEmail($subject, $bodyText)
    {
        try
        {
            $mail = new Zend_Mail('UTF-8');
            $config = Zend_Registry::get('config');
            $mail->addTo($config->support->email, self::checkName($config->support->from));
            $mail->setFrom('noreply@gogoverde.com', self::checkName('GoGoVerde'));
            $mail->setBodyText($bodyText);
            $mail->setSubject($subject);
            $mail->send();
        }
        catch (Exception $e)
        {             
            $translatedMessage = GgvUtil::translate('Unable to send email.');
            throw new GgvMailException($translatedMessage, 0, $e);
        }
    }
    
    public static function createRegistrationIdFromEmail($emailAddress, $salt)
    {
        $formatted_email = preg_replace('/(-|\@|\.)/', '', $emailAddress);
        return md5("$salt $formatted_email");
    }       
}
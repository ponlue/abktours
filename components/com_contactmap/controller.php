<?php
	/*
	* ContactMap Component Google Map for Joomla! 1.5.x
	* Version 3.9
	* Creation date: Octobre 2010
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die();
jimport('joomla.application.component.controller');

class ContactMapsController extends JController
{
	function display()
	{
		$view = JRequest::getVar('view', '', '', 'str');
//die(print_r($view));
		switch ($view) {
/*			case 'contactmap' :
			case 'contact' :
				$view = & $this->getView( 'contactmap', 'html');
				$view->setModel( $this->getModel( 'contactmap' ), true );
				$view->display();
				break;
*/			default :
				parent::display();
		};
	}

	function submit()
	{
		global $mainframe;
        $params = clone($mainframe->getParams('com_contactmap'));

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Initialize some variables
		$db			= & JFactory::getDBO();
		$SiteName	= JURI::base();

		$default	= JText::_( 'CONTACTMAP_EMAIL_RECU_DE').' : '.$SiteName;
		$contact	= JRequest::getVar( 'code',					'',			'post' );
		$uri64		= JRequest::getVar( 'code2',				'',			'post' );
		$name		= JRequest::getVar( 'contactmap_nom',		'',			'post' );
		$email		= JRequest::getVar( 'contactmap_email',		'',			'post' );
		$subject	= JRequest::getVar( 'contactmap_subject',	$default,	'post' );
		$body		= JRequest::getVar( 'contactmap_message',	'',			'post' );
		$emailCopy	= JRequest::getInt( 'contact_email_copy', 	0,			'post' );

		 // load the contact details
		$model		= &$this->getModel('contactmap');

		$email_to 	= base64_decode($contact);
		$uri 		= base64_decode($uri64);

		/*
		 * If there is no valid email address or message body then we throw an
		 * error and return false.
		 */
		jimport('joomla.mail.helper');
		if (!$email || !$body || (JMailHelper::isEmailAddress($email) == false))
		{
			$this->setError(JText::_('CONTACT_FORM_NC'));
			$this->display();
			return false;
		}

		//gestion du captcha
		$user   = & JFactory::getUser();
		if ($params->get('contactmap_afficher_captcha')&&!$user->get('gid')) { 
			if(count($_POST)>0){
				if(isset($_SESSION['contactmap-captcha-code'])) {
					if ($_SESSION['contactmap-captcha-code'] == @$_POST['keystring']){
						//$captacha=true;
					}else{
						JError::raiseWarning(0, JText::_('CONTACTMAP_CAPTCHA'));
						$this->setRedirect(JRoute::_($uri, false), "");
						return false;
						//$captacha=false;
					}
				}
			}
			unset($_SESSION['contactmap-captcha-code']);
		};

		$MailFrom 	= $mainframe->getCfg('mailfrom');
		$FromName 	= $mainframe->getCfg('fromname');

		// Prepare email body
        $myMessage = JText::_('CONTACTMAP_EMAIL_RECU_DE').' '.$name.'<'.$email.'> ';
        $myMessage.= JText::_('CONTACTMAP_EMAIL_RECU_PAR').' '.$SiteName.' '."\r\n\r\n".stripslashes($body);

		$mail = JFactory::getMailer();

		$mail->addRecipient( $email_to );
		$mail->setSender( array( $email, $name ) );
		$mail->setSubject( $FromName.': '.$subject );
		$mail->setBody( $myMessage );

		$sent = $mail->Send();

		// check whether email copy function activated
		if ( $emailCopy )
		{
			$copyText 		= JText::_('Copy of:')."\r\n\r\n";
			$copyText 		.= $myMessage;
			$copySubject 	= JText::_('Copy of:')." ".$subject;

			$mail = JFactory::getMailer();

			$mail->addRecipient( $email );
			$mail->setSender( array( $MailFrom, $FromName ) );
			$mail->setSubject( $copySubject );
			$mail->setBody( $copyText );

			$sent = $mail->Send();
		}

		$msg = JText::_( 'CONTACTMAP_EMAIL_MERCI');
		$this->setRedirect(JRoute::_($uri, false), $msg);
	}

}

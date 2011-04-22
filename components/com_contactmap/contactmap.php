<?php
	/*
	* ContactMap Component Google Map for Joomla! 1.5.x
	* Version 3.3
	* Creation date: Aout 2009
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: fayauxlogescpa@gmail.com
	* License GNU/GPL
	*/

defined('_JEXEC') or die('Restricted access');

$task = JRequest::getVar('task', '', '', 'str');
if ($task== 'captacha') {
	require_once (JPATH_SITE.'/components/com_contactmap/libraries/contactmap.captcha.php');
	ContactMapCaptcha::image();
};

require_once (JPATH_COMPONENT.DS.'controller.php');
if($controller = JRequest::getWord('controller')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}
$classname	= 'ContactMapsController'.ucfirst($controller);
$controller = new $classname( );

$controller->execute(JRequest::getCmd('task'));

$controller->redirect();

?>
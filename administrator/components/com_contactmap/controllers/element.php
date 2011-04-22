<?php
	/*
	* ContactMap Component Google Map for Joomla! 1.5.x
	* Version 1.0
	* Creation date: Aout 2009
	* Author: Fabrice4821 - www.gmapfp.francejoomla.net
	* Author email: fayauxlogescpa@gmail.com
	* License GNU/GPL
	*/

defined('_JEXEC') or die();

class ContactMapsControllerElement extends ContactMapsController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

	}

	function element()
	{
		JRequest::setVar( 'view', 'element_lieu' );
		JRequest::setVar( 'layout', 'element'  );

		parent::display();
	}

}
?>

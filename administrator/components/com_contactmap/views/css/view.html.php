<?php
    /*
    * ContactMap Component Google Map for Joomla! 1.5.x
    * Version 3.4
    * Creation date: Aout 2009
    * Author: Fabrice4821 - www.gmapfp.francejoomla.net
    * Author email: fayauxlogescpa@gmail.com
    * License GNU/GPL
    */

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContactMapsViewCSS extends JView
{
    function display($tpl = null)
    {
        JToolBarHelper::title(   JText::_( 'CONTACTMAP_CSS_MANAGER' ).': <small><small>[CSS]</small></small>', 'themes.png' );
        JToolBarHelper :: custom( 'saveCss', 'save.png', 'save.png', 'Save', false, false );


        parent::display($tpl);
    }
}

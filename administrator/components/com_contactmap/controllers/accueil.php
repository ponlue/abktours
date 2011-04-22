<?php
    /*
    * ContactMap Component Google Map for Joomla! 1.5.x
    * Version 1.0
    * Creation date: Avril 2010
    * Author: Fabrice4821 - www.gmapfp.francejoomla.net
    * Author email: fayauxlogescpa@gmail.com
    * License GNU/GPL
    */

defined('_JEXEC') or die();

class ContactMapsControllerAccueil extends ContactMapsController
{
    /**
     * constructor (registers additional tasks to methods)
     * @return void
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * display the edit form
     * @return void
     */
    function view()
    {
        JRequest::setVar( 'view', 'accueil' );
        JRequest::setVar( 'layout', 'form'  );
        JRequest::setVar('hidemainmenu', 0);

        parent::display();
    }

}
?>

<?php
    /*
    * ContactMap Component Google Map for Joomla! 1.5.x
    * Version 3.4
    * Creation date: Aout 2009
    * Author: Fabrice4821 - www.gmapfp.francejoomla.net
    * Author email: fayauxlogescpa@gmail.com
    * License GNU/GPL
    */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

class ContactMapsModelCss extends JModel
{

    /**
     * Method to store configuration
     * @return boolean value
     */
     
    function store()
    {   

        $file           = JPATH_COMPONENT_SITE.DS.'views/contactmap'.DS.'contactmap.css';
        $csscontent     = JRequest::getVar('csscontent', '', 'post', 'string', JREQUEST_ALLOWRAW);

        if( $fp = @fopen( $file, 'w' )) {
            fputs( $fp, stripslashes( $csscontent ) );
            fclose( $fp );
            return true;
        }else{
            return false;
        }

    }


}
?>
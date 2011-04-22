<?php
    /*
    * ContactMap Component Google Map for Joomla! 1.5.x
    * Version 6.6
    * Creation date: Avril 2010
    * Author: Fabrice4821 - www.gmapfp.francejoomla.net
    * Author email: fayauxlogescpa@gmail.com
    * License GNU/GPL
    */
class ContactMapHelper
{
    function saveContactMapPrep( &$row )
    {
        // Get submitted text from the request variables
        $text_horaires_prix = JRequest::getVar( 'text_horaires_prix', '', 'post', 'string', JREQUEST_ALLOWRAW );
        $text_message       = JRequest::getVar( 'text_message', '', 'post', 'string', JREQUEST_ALLOWRAW );
        $text_link          = JRequest::getVar( 'text_link', '', 'post', 'string', JREQUEST_ALLOWRAW );

        // Clean text for xhtml transitional compliance
        $text_horaires_prix     = str_replace( '<br>', '<br />', $text_horaires_prix );
        $text_message       = str_replace( '<br>', '<br />', $text_message );
        $text_link      = str_replace( '\\', '/', $text_link );

        // Search for the {readmore} tag and split the text up accordingly.
        $pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
        $tagPos = preg_match($pattern, $text_message);

        if ( $tagPos == 0 )
        {
            $row->misc = $text_message;
        } else
        {
            list($row->misc, $row->message) = preg_split($pattern, $text_message, 2);
        }

        $row->horaires_prix = $text_horaires_prix;
        $row->link  = $text_link;

        return true;
    }

}

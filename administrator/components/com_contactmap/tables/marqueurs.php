<?php
    /*
    * ContactMap Component Google Map for Joomla! 1.5.x
    * Version 1.0
    * Creation date: Aout 2009
    * Author: Fabrice4821 - www.gmapfp.francejoomla.net
    * Author email: fayauxlogescpa@gmail.com
    * License GNU/GPL
    */

    defined('_JEXEC') or die('Restricted access');
    
    class TableMarqueurs extends JTable
    {
        var $id = 0;
        var $nom = null;
        var $url = null;
        var $published = 0;

        function TableMarqueurs( &$db ) {
            parent::__construct('#__contactmap_marqueurs', 'id', $db);
        }
    }
?>
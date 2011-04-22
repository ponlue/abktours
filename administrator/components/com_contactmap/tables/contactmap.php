<?php
    /*
    * ContactMap Component Google Map for Joomla! 1.5.x
    * Version 2.0
    * Creation date: Aout 2009
    * Author: Fabrice4821 - www.gmapfp.francejoomla.net
    * Author email: fayauxlogescpa@gmail.com
    * License GNU/GPL
    */

defined('_JEXEC') or die('Restricted access');

class TableContactMap extends JTable
{
    /** @var int Primary key */
    var $id                 = 0;
    var $name               = '';
    var $alias              = '';
    var $con_position       = '';
    var $address            = '';
    var $suburb             = '';
    var $state		        = '';
    var $postcode	        = '';
    var $country            = '';
    var $telephone          = '';
    var $mobile             = '';
    var $fax                = '';
    var $email_to           = '';
    var $webpage            = '';
    var $image              = '';
    var $imagepos 			= null;
    var $misc               = '';
    var $message            = '';
    var $horaires_prix      = '';
    var $link               = '';
    var $icon               = '';
    var $icon_label         = '';
    var $article_id         = '';
    var $affichage          = '';
    var $marqueur           = '';
    var $glng               = '';
    var $glat               = '';
    var $gzoom              = '';
    var $catid              = '';
    var $user_id             = '';
    var $published          = 0;
    var $checked_out        = 0;
    var $metadesc           = '';
    var $metakey            = '';
    var $ordering           = null;
    var $default_con 		= null;         
    var $checked_out_time 	= '0000-00-00 00:00:00';         
    var $params 			= null;         
    var $access 			= null;         

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function TableContactMap(& $db) {
        parent::__construct('#__contact_details', 'id', $db);
    }

    function check()
    {
        //Remove all HTML tags from the title
        $filter = new JFilterInput(array(), array(), 0, 0);
        $this->name = $filter->clean($this->name);

        if(empty($this->alias)) {
            $this->alias = $this->name;
        }
        $this->alias = JFilterOutput::stringURLSafe($this->alias);
        if(trim(str_replace('-','',$this->alias)) == '') {
            $datenow =& JFactory::getDate();
            $this->alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
        }

        return true;
    }
}
?>

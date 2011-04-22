<?php
    /*
    * GMapFP Component Google Map for Joomla! 1.5.x
    * Version 3.0
    * Creation date: Juin 2010
    * Author: Fabrice4821 - www.gmapfp.francejoomla.net
    * Author email: fayauxlogescpa@gmail.com
    * License GNU/GPL
    */

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContactMapsViewContactMap extends JView
{
    function display($tpl = null)
    {
        global $mainframe, $option;

        $contactmap =& $this->get('Data');
        $marqueurs  =& $this->get('Marqueurs');
        $isNew      = ($contactmap->id < 1);
        $catid      =& $this->get('categorie');
        $catid      =@ $catid[0]->id;

        $config =& JComponentHelper::getParams('com_contactmap');
        $lang = JFactory::getLanguage(); 
        $tag_lang=(substr($lang->getTag(),0,2)); 
		
        $mainframe->addCustomHeadTag( '<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />'); 
        $mainframe->addCustomHeadTag( '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true&language='.$tag_lang.'"></script>'); 

        $text = $isNew ? JText::_( 'CONTACTMAP_NEW' ) : JText::_( 'CONTACTMAP_EDIT' );
        JToolBarHelper::title(   JText::_( 'CONTACTMAP_LIEUX_MANAGER' ).': <small>[ ' . $text.' ]</small>', 'frontpage.png' );
        JToolBarHelper::save();
        JToolBarHelper::apply();
        if ($isNew)  {
            JToolBarHelper::cancel();
        } else {
            // for existing items the button is renamed `close`
            JToolBarHelper::cancel( 'cancel', 'Close' );
        }

        // build the html select list for ordering
        $query = 'SELECT ordering AS value, name AS text'
            . ' FROM #__contact_details'
            //. ' WHERE catid = ' . (int) $contactmap->catid
            . ' ORDER BY ordering';

        $lists['ordering']          = JHTML::_('list.specificordering',  $contactmap, $contactmap->id, $query );

		// build list of categories
		$lists['catid'] 			= JHTML::_('list.category',  'catid', 'com_contact_details', intval( $contactmap->catid ) );
		// build the html select list for the group access
		$lists['access'] 			= JHTML::_('list.accesslevel',  $contactmap );
		// build list of users
		$lists['user_id'] 			= JHTML::_('list.users',  'user_id', $contactmap->user_id, 1, NULL, 'name', 0 );

        $this->assignRef('contactmap',      $contactmap);
        $this->assignRef('marqueurs',   $marqueurs);
        $this->assignRef('config',  $config);
        $this->assignRef('lists',       $lists);
        $this->assignRef('catid',       $catid);

        parent::display($tpl);
    }
}

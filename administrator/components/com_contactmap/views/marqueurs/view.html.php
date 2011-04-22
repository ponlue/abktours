<?php
    /*
    * GMapFP Component Google Map for Joomla! 1.5.x
    * Version 1.0
    * Creation date: Aout 2009
    * Author: Fabrice4821 - www.gmapfp.francejoomla.net
    * Author email: fayauxlogescpa@gmail.com
    * License GNU/GPL
    */

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContactMapsViewMarqueurs extends JView
{
    function display($tpl = null)
    {
        global $mainframe, $option;
        $controller = JRequest::getWord('controller');

        if (JRequest::getWord('task')=='view') 
        {
            JToolBarHelper::title(   JText::_( 'CONTACTMAP_MARQUEURS_MANAGER' ), 'checkin.png' );
            JToolBarHelper::publishList();
            JToolBarHelper::unpublishList();
            JToolBarHelper::deleteList();
            JToolBarHelper::editListX();
            JToolBarHelper::addNewX();
        
            // Get data from the model
            $marqueurs      = & $this->get('List');
        }
        else
        {
            // Get data from the model
            $marqueurs      =& $this->get('Data');
            $isNew      = ($marqueurs->id < 1);
    
            $text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
            JToolBarHelper::title(   JText::_( 'CONTACTMAP_MARQUEURS_MANAGER' ).': <small>[ ' . $text.' ]</small>', 'checkin.png' );
            JToolBarHelper::save();
            JToolBarHelper::apply();
            if ($isNew)  {
                JToolBarHelper::cancel();
        } else {
            // for existing items the button is renamed `close`
            JToolBarHelper::cancel( 'cancel', 'Close' );
        }
        }
        JHTML::_('behavior.tooltip');

        $this->assignRef('marqueurs', $marqueurs);
        
        $pageNav = & $this->get( 'Pagination' );                
        $this->assignRef('pageNav', $pageNav);

        $filter_order       = $mainframe->getUserStateFromRequest( $option.$controller.'filter_order', 'filter_order', 'nom', 'cmd');
        $filter_order_Dir   = $mainframe->getUserStateFromRequest( $option.$controller.'filter_order_Dir', 'filter_order_Dir', '', 'word');
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;
        $this->assignRef('lists', $lists);

        parent::display($tpl);

    }
}

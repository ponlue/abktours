<?php
    /*
    * ContactMap Component Google Map for Joomla! 1.5.x
    * Version 6.6
    * Creation date: Avril 2010
    * Author: Fabrice4821 - www.gmapfp.francejoomla.net
    * Author email: fayauxlogescpa@gmail.com
    * License GNU/GPL
    */

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContactMapsViewContactMaps extends JView
{
    function display($tpl = null)
    {
        global $mainframe, $option;

        JToolBarHelper::title(   JText::_( 'CONTACTMAP_LIEUX_MANAGER' ), 'user.png' );
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::customX( 'copy', 'copy.png', 'copy_f2.png', 'Copy' );
        JToolBarHelper::deleteList();
        JToolBarHelper::editListX();
        JToolBarHelper::addNewX();
        JToolBarHelper::divider();
        JToolBarHelper::preferences('com_contactmap', '620');


        JHTML::_('behavior.tooltip');

        // Get data from the model
        $items      = & $this->get( 'Data');
        $total      = & $this->get( 'Total');
        $pageNav    = & $this->get( 'Pagination' );

        $filtrevilles = array();
        $filtrevilles[] = JHTML::_('select.option', '-- '.JText::_( 'CONTACTMAP_VILLE_FILTRE' ).' --' );
                foreach($this->get('listville') as $temp) {
                    $filtrevilles[] = JHTML::_('select.option', $temp->suburb );
                }

        $filtredepartements = array();
        $filtredepartements[] = JHTML::_('select.option', '-- '.JText::_( 'CONTACTMAP_DEPARTEMENT_FILTRE' ).' --' );
                foreach($this->get('listdepartement') as $temp) {
                    $filtredepartements[] = JHTML::_('select.option', $temp->state );
                }

        $filter_order       = $mainframe->getUserStateFromRequest( $option.'filter_order',      'filter_order',     'a.ordering',   'cmd' );
        $filter_order_Dir   = $mainframe->getUserStateFromRequest( $option.'filter_order_Dir',  'filter_order_Dir',     '',                                             'word');
        $search             = $mainframe->getUserStateFromRequest( $option.'search',            'search',               '',                                             'string' );
        $filtreville        = $mainframe->getUserStateFromRequest( $option.'filtreville',       'filtreville',          '-- '.JText::_( 'CONTACTMAP_VILLE' ).' --',         'string' );
        $filtredepartement  = $mainframe->getUserStateFromRequest( $option.'filtredepartement', 'filtredepartement',    '-- '.JText::_( 'CONTACTMAP_DEPARTEMENT' ).' --',   'string' );

        $lists['ville']         = JHTML::_('select.genericlist', $filtrevilles, 'filtreville', 'size="1" class="inputbox" onchange="form.submit()"', 'value', 'text', $filtreville );
        $lists['departement']   = JHTML::_('select.genericlist', $filtredepartements, 'filtredepartement', 'size="1" class="inputbox" onchange="form.submit()"', 'value', 'text', $filtredepartement );

        // table ordering
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;

        // search filter
        $lists['search'] = $search;
        
        $this->assignRef('lists', $lists);
        $this->assignRef('items',   $items);
        $this->assignRef('pageNav', $pageNav);

        parent::display($tpl);

    }
}

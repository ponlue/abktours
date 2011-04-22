<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: view.html.php 2 2010-04-13 13:37:46Z WEB $
 * @copyright       Copyright 2009-2010, $Author: WEB $
 * @license         GNU General Public License (GNU GPL) GPLv2, 
 *                  - see http://www.demo-page.de/en/license-conditions.html
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 *    See the GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @link            http://www.demo-page.de
 * @package         TRAVELbook Component
 * @revision        $Revision: 2 $
 * @lastmodified    $Date: 2010-04-13 15:37:46 +0200 (Di, 13 Apr 2010) $
*/

/*** No direct access ***/
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.html.pane');
jimport( 'joomla.application.component.view');

/**
 * Travelbook View
 *
 * @package    Travelbook
 * @subpackage administrator
 */ 
class TravelbooksViewServices extends JView
{
	/** 
	 * display method of Travelbook view
	 * @return void
	 **/
	function display($tpl = null)
	{
		$db =& JFactory::getDBO();
		
		$document = &JFactory::getDocument();
		$document->addStyleSheet( 'components/com_travelbook/assets/css/travelbook.css' );


		$service	=& $this->get('service');
		$isNew        = ($service->id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Hello' ).': <small><small>[ ' . $text.' ]</small></small>' );


// Title and Ikons
		$history = Jrequest::getVar('history','services.cancel');
		$task = JRequest::getCmd('task', 'travelbooks.show');
		switch ($task) {
			case 'services.show':
					JToolBarHelper::title(   JText::_( 'TB Services' ).' <small><small>[ ' .JText::_( 'TB List' ).' ]</small></small>' , 'service');
					JToolBarHelper::publish( 'services.publish', 'Publish' );
					JToolBarHelper::unpublish( 'services.unpublish', 'Unpublish' );
					JToolBarHelper::deleteListX( JText::_( 'TB sure' ),'services.delete', 'Delete' );
					JToolBarHelper::editListX( 'services.edit', 'Edit' );
					JToolBarHelper::addNewX( 'services.add', 'New' );
				break;
			case 'services.detail':
					JToolBarHelper::title(   JText::_( 'TB Services' ).' <small><small>[ ' .JText::_( 'TB Details' ).' ]</small></small>' , 'service');
					JToolBarHelper::save( 'services.save', 'Save' );
					JToolBarHelper::apply( 'services.apply', 'Apply' );
					JToolBarHelper::cancel( $history, 'Cancel' );
					JToolBarHelper::back( 'Back' );
				break;
			case 'services.add':
					JToolBarHelper::title(   JText::_( 'TB Services' ).' <small><small>[ ' .JText::_( 'TB Add new Service' ).' ]</small></small>' , 'service');
					JToolBarHelper::save( 'services.save', 'Save' );
					JToolBarHelper::apply( 'services.apply', 'Apply' );
					JToolBarHelper::cancel( 'services.cancel', 'Cancel' );
					JToolBarHelper::back( 'Back' );
				break;
		}

// Filters and co
		global $mainframe, $option_services, $orderby, $where, $pageNav;
	
		$filter_order		= $mainframe->getUserStateFromRequest( $option_services.'filter_order', 		'filter_order', 	'services.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option_services.'filter_order_Dir',	'filter_order_Dir',	'',					'word' );
		$filter_state 		= $mainframe->getUserStateFromRequest( $option_services.'filter_state', 		'filter_state', 	'',					'word' );
		$filter_catid 		= $mainframe->getUserStateFromRequest( $option_services.'filter_catid', 		'filter_catid',		0,					'int' );
		$filter_srv_type	= $mainframe->getUserStateFromRequest( $option_services.'filter_srv_type', 	'filter_srv_type',	0,					'int' );
		$search 			= $mainframe->getUserStateFromRequest( $option_services.'search', 			'search', 			'',					'string' );
		$search 			= JString::strtolower( $search );
	
		$limit				= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart			= $mainframe->getUserStateFromRequest($option_services.'.limitstart', 'limitstart', 0, 'int');
	
		$where = array();
	
		if ( $search ) {
			$where[] = 'services.name LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		if ( $filter_catid ) {
			$where[] = 'services.catid = '.(int) $filter_catid;
		}

		if ( $filter_srv_type ) {
			$where[] = 'services.srv_type = '.(int) $filter_srv_type;
		}

		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'services.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'services.published = 0';
			}
		}
	
		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		if ($filter_order == 'services.ordering'){
			$orderby 	= ' ORDER BY  categories.title, types.title, services.ordering';
		} else {
			$orderby 	= ' ORDER BY categories.title, '. $filter_order .' '. $filter_order_Dir .', types.title, services.ordering';
		}

// get the total number of records
		$total	=& $this->get('total');
		$this->assignRef('total', $total);	

		jimport('joomla.html.pagination');

		$pageNav = new JPagination( $total, $limitstart, $limit );

// get the subset (based on limits) of required records
//var_dump($filter_order);
		$services	=& $this->get('subset');
		$this->assignRef('services', $services);	
//var_dump($services);

// get the subset (based on limits) of required records
//		$service	=& $this->get('service');
		$this->assignRef('service', $service);	

// build list of service categories
		$javascript = 'onchange="document.adminForm.submit();"';
		$lists['catid'] = JHTML::_('list.category',  'filter_catid', 'com_service_details', intval( $filter_catid ), $javascript );
// build list of service types
		$javascript = 'onchange="document.adminForm.submit();"';
		$lists['srv_type'] = JHTML::_('list.category',  'filter_srv_type', 'com_service_type', intval( $filter_srv_type ), $javascript );
// state filter
		$lists['state']	= JHTML::_('grid.state',  $filter_state );
// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
// search filter
		$lists['search']= $search;
		$this->assignRef('lists',		$lists);	

// Details
// build list of service categories
	$details['catid'] = JHTML::_('list.category',  'catid', 'com_service_details', intval( $service->catid ) );
// build list of service types
	$details['srv_type'] = JHTML::_('list.category',  'srv_type', 'com_service_type', intval( $service->srv_type ) );
// build the html radio buttons for published
	$details['published'] = JHTML::_('select.booleanlist',  'published', '', $service->published );
	$details['checked'] = JHTML::_('select.booleanlist',  'checked', '', $service->checked );
	
// build the html select list for ordering
	$query = 'SELECT ordering AS value, name AS text'
	. ' FROM #__tb_services'
	. ' WHERE published >= 0'
	. ' AND catid = '.(int) $service->catid
	. ' ORDER BY ordering'
	;
	$details['ordering'] 			= JHTML::_('list.specificordering',  $service, $service->id, $query );

	$this->assignRef('details',		$details);	

	parent::display($tpl);
		
	}

/**
* render Seasons
*/
	 function renderTours () {
	 	$output = '';

		$output = '<div style="padding: 5px;">';
		$output .= "<table class='adminlist'>\n";

		$output .= "	<thead>\n";
		$output .= "		<tr class='sortable'>\n";
		$output .= "			<th width='10'> ".JText::_( 'Num' )."</th>\n";
		$output .= "			<th  nowrap='nowrap' style='text-align: left;'> ".JText::_( 'TB Tour' )."</th>\n";
		$output .= "			<th  width='30%' nowrap='nowrap' style='text-align: left;'> ".JText::_( 'TB Category' )."</th>\n";
		$output .= "			<th  width='20%' nowrap='nowrap' style='text-align: center;'> ".JText::_( 'TB Year' )."</th>\n";
		$output .= "		</tr>\n";
		$output .= "	</thead>\n";

		$tours	=& $this->get('tours');
		$i = 0;
		foreach ($tours as $tour) {
			$i++;
			$link = JRoute::_( 'index.php?option=com_travelbook&task=tours.detail&tid[]='. $tour->id );

			$output .= "	<tr class='row".$tour->id."'>\n";
			$output .= "		<td>\n";
			$output .= $i;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: left;'>\n";
			$output .= "<a href='".$link."'>".$tour->title."</a> ";
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: left;'>\n";
			$output .= $tour->category;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: center;'>\n";
			$output .= $tour->year;
			$output .=  "		</td>\n";
			$output .=  "	</tr>\n";
		}

		$output .= "</table>\n";
		$output .= '</div>';
		
		return $output;
	 }
	 		
}
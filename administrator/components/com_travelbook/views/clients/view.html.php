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
class TravelbooksViewClients extends JView
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
		$document->addStyleSheet( 'components/com_travelbook/assets/css/calendar.css' );
		$document->addScript( 'components/com_travelbook/assets/js/calendar.js' );

		$client	=& $this->get('client');
		$isNew        = ($client->id < 1);
		
// Title and Ikons
		$history = Jrequest::getVar('history','clients.cancel');
		$task = JRequest::getCmd('task', 'travelbooks.show');
		switch ($task) {
			case 'clients.show':
					JToolBarHelper::title(   JText::_( 'TB Client' ).' <small><small>[ ' .JText::_( 'TB List' ).' ]</small></small>' , 'client');
					JToolBarHelper::publish( 'clients.publish', 'Publish' );
					JToolBarHelper::unpublish( 'clients.unpublish', 'Unpublish' );
					JToolBarHelper::deleteListX( JText::_( 'TB Are You sure?' ),'clients.delete', 'Delete' );
					JToolBarHelper::editListX( 'clients.edit', 'Edit' );
					JToolBarHelper::addNewX( 'clients.add', 'New' );
				break;
			case 'clients.detail':
					JToolBarHelper::title(   JText::_( 'TB Client' ).' <small><small>[ ' .JText::_( 'TB Details' ).' ]</small></small>' , 'client');
					JToolBarHelper::save( 'clients.save', 'Save' );
					JToolBarHelper::apply( 'clients.apply', 'Apply' );
					JToolBarHelper::cancel( $history, 'Cancel' );
				break;
			case 'clients.add':
					JToolBarHelper::title(   JText::_( 'TB Client' ).' <small><small>[ ' .JText::_( 'TB Add new Client' ).' ]</small></small>' , 'client');
					JToolBarHelper::save( 'clients.save', 'Save' );
					JToolBarHelper::apply( 'clients.apply', 'Apply' );
					JToolBarHelper::cancel( 'clients.cancel', 'Cancel' );
				break;
		}

// Filters and co
		global $mainframe, $option_client, $orderby, $where, $pageNav;
	
		$filter_order		= $mainframe->getUserStateFromRequest( $option_client.'filter_order', 		'filter_order', 	'clients.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option_client.'filter_order_Dir',	'filter_order_Dir',	'',					'word' );
		$filter_state 		= $mainframe->getUserStateFromRequest( $option_client.'filter_state', 		'filter_state', 	'',					'word' );
		$search 			= $mainframe->getUserStateFromRequest( $option_client.'search', 			'search', 			'',					'string' );
		$search 			= JString::strtolower( $search );
	
		$limit				= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart			= $mainframe->getUserStateFromRequest($option_client.'.limitstart', 'limitstart', 0, 'int');

		$where = array();
	
		if ( $search ) {
			$where[] = 'clients.last_name LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ).' OR clients.first_name LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'clients.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'clients.published = 0';
			}
		}
	
		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		if ($filter_order == 'clients.ordering'){
			$orderby 	= ' ORDER BY clients.ordering';
		} else {
			$orderby 	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir .', clients.ordering';
		}
	
// get the total number of records
		$total	=& $this->get('total');
		$this->assignRef('total', $total);	

		jimport('joomla.html.pagination');

		$pageNav = new JPagination( $total, $limitstart, $limit );

// get the subset (based on limits) of required records
		$clients	=& $this->get('subset');
		$this->assignRef('clients', $clients);	

// get the subset (based on limits) of required records
		$client	=& $this->get('client');
		$this->assignRef('client', $client);	

// build list of categories
		$javascript = 'onchange="document.adminForm.submit();"';
		$lists['catid'] = JHTML::_('list.category',  'filter_catid', 'com_client_details', intval( $filter_catid ), $javascript );
// state filter
		$lists['state']	= JHTML::_('grid.state',  $filter_state );
// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
// search filter
		$lists['search']= $search;
		$this->assignRef('lists',		$lists);	

// Details
// build list of categories
	$details['catid'] = JHTML::_('list.category',  'catid', 'com_client_details', intval( $client->catid ) );
// build the html radio buttons for published
	$details['published'] = JHTML::_('select.booleanlist',  'published', '', $client->published );
// build the html select list for ordering
	$query = 'SELECT ordering AS value, last_name AS text'
	. ' FROM #__tb_clients'
	. ' WHERE published >= 0'
//	. ' AND catid = '.(int) $client->catid
	. ' ORDER BY ordering'
	;
	$details['ordering'] 			= JHTML::_('list.specificordering',  $client, $client->id, $query );

	$this->assignRef('details',		$details);	

	parent::display($tpl);
		
	}

/**
* render Seasons
*/
	 function renderSeasons () {
	 	$output = '';

		$output = '<div style="padding: 5px;">';
		$output .= "<table class='adminlist'>\n";

		$output .= "	<thead>\n";
		$output .= "		<tr class='sortable'>\n";
		$output .= "			<th width='10'> ".JText::_( 'Num' )."</th>\n";
		$output .= "			<th  width='33%' nowrap='nowrap' style='text-align: left;'> ".JText::_( 'TB Tour' )."</th>\n";
		$output .= "			<th  width='33%' nowrap='nowrap' style='text-align: left;'> ".JText::_( 'TB Departure' )."</th>\n";
		$output .= "			<th  width='33%' nowrap='nowrap' style='text-align: right;'> ".JText::_( 'TB Arrival' )."</th>\n";
		$output .= "		</tr>\n";
		$output .= "	</thead>\n";

		$seasons	=& $this->get('seasons');
		$i = 0;
		foreach ($seasons as $season) {
			$i++;
			$link = JRoute::_( 'index.php?option=com_travelbook&task=seasons.detail&tid[]='. $season->id );

			$output .= "	<tr class='row".$season->id."'>\n";
			$output .= "		<td>\n";
			$output .= $i;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: left;'>\n";
			$output .= $season->title;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: left;'>\n";
			$output .= "<a href='".$link."'>".JHTML::_('date', $season->departure, JText::_('DATE_FORMAT_LC1'))."</a> ";
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: right;'>\n";
			$output .= JHTML::_('date', $season->arrival, JText::_('DATE_FORMAT_LC1'))." ";
			$output .=  "		</td>\n";
			$output .=  "	</tr>\n";
		}

		$output .= "</table>\n";
		$output .= '</div>';
		
		return $output;
	 }
	
/**
* render Guests
*/
	 function renderGuests () {
	 	$output = '';

		$output = '<div style="padding: 5px;">';
		$output .= "<table class='adminlist'>\n";

		$output .= "	<thead>\n";
		$output .= "		<tr class='sortable'>\n";
		$output .= "			<th width='10'> ".JText::_( 'Num' )."</th>\n";
		$output .= "			<th  width='10%' nowrap='nowrap' style='text-align: left;'> ".JText::_( 'TB Title' )."</th>\n";
		$output .= "			<th  width='30%' nowrap='nowrap' style='text-align: left;'> ".JText::_( 'TB First Name' )."</th>\n";
		$output .= "			<th  width='30%' nowrap='nowrap' style='text-align: right;'> ".JText::_( 'TB Last Name' )."</th>\n";
		$output .= "			<th  width='30%' nowrap='nowrap' style='text-align: right;'> ".JText::_( 'TB Birthdate' )."</th>\n";
		$output .= "		</tr>\n";
		$output .= "	</thead>\n";

		$guests	=& $this->get('guests');
		$i = 0;
		foreach ($guests as $guest) {
			$i++;
//			$link = JRoute::_( 'index.php?option=com_travelbook&task=guests.detail&tid[]='. $season->id );

			$output .= "	<tr class='row".$guest->id."'>\n";
			$output .= "		<td>\n";
			$output .= $i;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: left;'>\n";
			$output .= $guest->title;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: left;'>\n";
			$output .= $guest->first_name;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: right;'>\n";
			$output .= $guest->last_name;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: right;'>\n";
			$output .= JHTML::_('date', $guest->birthdate, JText::_('DATE_FORMAT_LC1'))." ";
			$output .=  "		</td>\n";
			$output .=  "	</tr>\n";
		}

		$output .= "</table>\n";
		$output .= '</div>';
		
		return $output;
	 }

/**
* render Guests
*/
	 function renderDetails () {
	 	$output = '';

		$output = '<div style="padding: 5px;">';
		$output .= "<table class='adminlist'>\n";

		$output .= "	<thead>\n";
		$output .= "		<tr class='sortable'>\n";
		$output .= "			<th  width='4%' nowrap='nowrap' style='text-align: center;'> ".JText::_( 'TB NUM SINGLE' )."</th>\n";
		$output .= "			<th  width='4%' nowrap='nowrap' style='text-align: center;'> ".JText::_( 'TB NUM DOUBLE' )."</th>\n";
		$output .= "			<th  width='40%' nowrap='nowrap' style='text-align: left;'> ".JText::_( 'TB Hotel' )."</th>\n";
		$output .= "			<th  width='10%' nowrap='nowrap' style='text-align: left;'> ".JText::_( 'TB Feeder' )."</th>\n";
		$output .= "			<th  width='30%' nowrap='nowrap' style='text-align: left;'> ".JText::_( 'TB Services' )."</th>\n";
		$output .= "			<th  width='24%' nowrap='nowrap' style='text-align: right;'> ".JText::_( 'TB TOTAL' )."</th>\n";
		$output .= "		</tr>\n";
		$output .= "	</thead>\n";

		$details	=& $this->get('details');
		$i = 0;
		foreach ($details as $detail) {

// prepare hotel
			if ($detail->hotel == 0) {
				$rend_hotel = "N/A".$detail->hotel;
			} else {
				$db			=& JFactory::getDBO();
				$query =	'SELECT #__tb_hotels.*, #__tb_jrn_htl.accomodation, #__tb_jrn_htl.rate '.
							'FROM #__tb_hotels INNER JOIN #__tb_jrn_htl ON #__tb_hotels.id = #__tb_jrn_htl.htl_id '.
							'WHERE #__tb_jrn_htl.id = '.$detail->hotel.''.
							';';
				$db->setQuery( $query );
				$hotel = $db->loadObjectList();
				$rend_hotel = $hotel[0]->name." (".$hotel[0]->chain.") - ".$hotel[0]->accomodation."x".number_format($hotel[0]->rate, 2, ',', '.');
			}
		
// prepare feeder
			if ($detail->feeder == 0) {
				$rend_feeder = "N/A";
			} else {
				$query =	'SELECT jos_tb_dat_prt.markup, jos_tb_feeder.iata '.
							'FROM jos_tb_feeder INNER JOIN jos_tb_dat_prt ON jos_tb_feeder.id = jos_tb_dat_prt.prt_id  '.
							'WHERE jos_tb_dat_prt.id = '.$detail->feeder.''.
							';';
				$db->setQuery( $query );
				$feeder = $db->loadObjectList();
				$rend_feeder = $feeder[0]->iata." - ".number_format($feeder[0]->markup, 0, ',', '.');
			}

			$output .= "	<tr class='row".$guest->id."'>\n";
			$output .= "		<td style='text-align: center;'>\n";
			$output .= $detail->single;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: center;'>\n";
			$output .= $detail->double;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: left;'>\n";
			$output .= $rend_hotel;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: left;'>\n";
			$output .= $rend_feeder;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: left;'>\n";
			$output .= $detail->services;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: right;'>\n";
			$output .= number_format($detail->total, 2, ',', '.');
			$output .=  "		</td>\n";
			$output .=  "	</tr>\n";
		}


		$output .= "</table>\n";
		$output .= '</div>';
		
		return $output;
	 }
	
}
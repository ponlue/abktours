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
class TravelbooksViewSeasons extends JView
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

		$season		=& $this->get('season');
		$isNew      = ($season->id < 1);
		$tours		=& $this->get('tours');
		
//		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
//		JToolBarHelper::title(   JText::_( 'Hello' ).': <small><small>[ ' . $text.' ]</small></small>' );


// Title and Ikons
		$task = JRequest::getCmd('task', 'travelbooks.show');
		switch ($task) {
			case 'seasons.show':
					JToolBarHelper::title(   JText::_( 'TB Seasons' ).' <small><small>[ ' .JText::_( 'TB List' ).' ]</small></small>' , 'season');
					JToolBarHelper::publish( 'seasons.publish', 'Publish' );
					JToolBarHelper::unpublish( 'seasons.unpublish', 'Unpublish' );
					JToolBarHelper::deleteListX( JText::_( 'TB Are You sure?' ),'seasons.delete', 'Delete' );
					JToolBarHelper::editListX( 'seasons.edit', 'Edit' );
					JToolBarHelper::addNewX( 'seasons.add', 'New' );
				break;
			case 'seasons.detail':
					JToolBarHelper::addNew( 'seasons.addfeeders', 'TB Add Feeders' );
					JToolBarHelper::divider();
					JToolBarHelper::title(   JText::_( 'TB Seasons' ).' <small><small>[ ' .JText::_( 'TB Details' ).' ]</small></small>' , 'season');
					JToolBarHelper::save( 'seasons.save', 'Save' );
					JToolBarHelper::apply( 'seasons.apply', 'Apply' );
					JToolBarHelper::cancel( 'seasons.cancel', 'Cancel' );
					JToolBarHelper::back( 'Back' );
				break;
			case 'seasons.add':
					JToolBarHelper::title(   JText::_( 'TB Seasons' ).' <small><small>[ ' .JText::_( 'TB Add new Season' ).' ]</small></small>' , 'season');
					JToolBarHelper::save( 'seasons.save', 'Save' );
					JToolBarHelper::apply( 'seasons.apply', 'Apply' );
					JToolBarHelper::cancel( 'seasons.cancel', 'Cancel' );
					JToolBarHelper::back( 'Back' );
				break;
		}

// Filters and co
		global $mainframe, $option_season, $orderby, $where, $pageNav;
	
		$filter_order		= $mainframe->getUserStateFromRequest( $option_season.'filter_order', 		'filter_order', 	'seasons.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option_season.'filter_order_Dir',	'filter_order_Dir',	'',					'word' );
		$filter_state 		= $mainframe->getUserStateFromRequest( $option_season.'filter_state', 		'filter_state', 	'',					'word' );

// get list of tours
		$tourTitle[] = JHTML::_('select.option',  0, JText::_('TB Tour Selection') );
		foreach( $tours as $tour ) {
			$tourTitle[] = JHTML::_('select.option',  $tour->id, $tour->title );
		}
		$filter_catid 		= $mainframe->getUserStateFromRequest( $option_season.'filter_catid', 		'filter_catid',	0,					'int' );
		$clist = JHTML::_('select.genericlist', $tourTitle, 'filter_catid', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $filter_catid );

		$search 			= $mainframe->getUserStateFromRequest( $option_season.'search', 			'search', 			'',					'string' );
		$search 			= JString::strtolower( $search );
	
		$limit				= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart			= $mainframe->getUserStateFromRequest($option_season.'.limitstart', 'limitstart', 0, 'int');
	
		$where = array();
	
		if ( $search ) {
			$where[] = 'tours.title LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		if ( $filter_catid ) {
			$where[] = 'tours.id = '.(int) $filter_catid;
		}

		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'seasons.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'seasons.published = 0';
			}
		}
	
		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		if ($filter_order == 'seasons.ordering'){
			$orderby 	= ' ORDER BY tours.title, seasons.ordering';
		} else {
			$orderby 	= ' ORDER BY tours.title, ' . $filter_order .' '. $filter_order_Dir .', seasons.ordering';
		}
	
// get the total number of records
		$total	=& $this->get('total');
		$this->assignRef('total', $total);	

		jimport('joomla.html.pagination');

		$pageNav = new JPagination( $total, $limitstart, $limit );

// get the subset (based on limits) of required records
		$seasons	=& $this->get('subset');
		$this->assignRef('seasons', $seasons);	

// get the subset (based on limits) of required records
//		$season	=& $this->get('season');
		$this->assignRef('season', $season);	

// build list of categories
		$lists['clist'] = $clist;
//		$javascript = 'onchange="document.adminForm.submit();"';
//		$lists['id'] = JHTML::_('list.category',  'filter_id', 'com_season_details', intval( $filter_id ), $javascript );
// state filter
		$lists['state']	= JHTML::_('grid.state',  $filter_state );
// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		$lists['catid']		= $filter_catid;
// search filter
		$lists['search']= $search;
		$this->assignRef('lists',		$lists);	

// Details
// build list of categories
	$details['catid'] = JHTML::_('list.category',  'catid', 'com_tour_details', intval( $tour->catid ) );
// build list of categories
	$clist = JHTML::_('select.genericlist', $tourTitle, 'jrn_id', 'class="inputbox" size="1"', 'value', 'text', intval( $season->jrn_id )  );
	$details['tours'] = $clist;
// build the html radio buttons for published
	$details['published'] = JHTML::_('select.booleanlist',  'published', '', $season->published );
// build the html select list for ordering
	$query = 'SELECT ordering AS value, departure AS text'
	. ' FROM #__tb_season'
	. ' WHERE published >= 0'
	. ' AND id = '.(int) $season->id
	. ' ORDER BY ordering;'
	;
	$details['ordering'] 			= JHTML::_('list.specificordering',  $season, $season->id, $query );

	$this->assignRef('details',		$details);	

	parent::display($tpl);
		
	}

/**
* render Clients
*/
	 function renderClients () {
	 	$output = '';

		$output = '<div style="padding: 5px;">';
		$output .= "<table class='adminlist'>\n";

		$output .= "	<thead>\n";
		$output .= "		<tr class='sortable'>\n";
		$output .= "			<th width='10'> ".JText::_( 'Num' )."</th>\n";
		$output .= "			<th  nowrap='nowrap' style='text-align: left;'> ".JText::_( 'TB Last Name' )."</th>\n";
		$output .= "			<th  width='35%' nowrap='nowrap' style='text-align: left;'> ".JText::_( 'TB First Name' )."</th>\n";
		$output .= "			<th  width='10%' nowrap='nowrap' style='text-align: right;'> ".JText::_( 'TB Title' )."</th>\n";
		$output .= "			<th  width='15%' nowrap='nowrap' style='text-align: right;'> ".JText::_( 'TB Birthdate' )."</th>\n";
		$output .= "		</tr>\n";
		$output .= "	</thead>\n";

		$clients	=& $this->get('clients');
		$i = 0;
		foreach ($clients as $client) {
			$i++;
			$link = JRoute::_( 'index.php?option=com_travelbook&task=clients.detail&tid[]='. $client->id );

			$output .= "	<tr class='row".$client->id."'>\n";
			$output .= "		<td>\n";
			$output .= $i;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: left;'>\n";
			$output .= "<a href='".$link."'>".$client->last_name."</a> ";
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: left;'>\n";
			$output .= $client->first_name;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: left;'>\n";
			$output .= $client->title;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: left;'>\n";
			$output .= $client->birthdate;
			$output .=  "		</td>\n";
			$output .=  "	</tr>\n";
		}

		$output .= "</table>\n";
		$output .= '</div>';
		
		return $output;
	 }

/**
* render Feeders
*/
	 function renderFeeders () {
	 	$output = '';

		$output = '<div style="padding: 5px;">';
		$output .= "<table class='adminlist'>\n";

		$output .= "	<thead>\n";
		$output .= "		<tr class='sortable'>\n";
		$output .= "			<th width='10'> ".JText::_( 'Num' )."</th>\n";
		$output .= "			<th  width='12%' nowrap='nowrap' style='text-align: left;'> ".JText::_( 'TB IATA' )."</th>\n";
		$output .= "			<th  nowrap='nowrap' style='text-align: left;'> ".JText::_( 'TB Feeder' )."</th>\n";
		$output .= "			<th  width='15%' nowrap='nowrap' style='text-align: right;'> ".JText::_( 'TB Supplement' )."</th>\n";
		$output .= "		</tr>\n";
		$output .= "	</thead>\n";

		$feeders	=& $this->get('feeders');
		$i = 0;
		foreach ($feeders as $feeder) {
			$i++;
			$link = JRoute::_( 'index.php?option=com_travelbook&task=feeders.detail&tid[]='. $feeder->id );

			$output .= "	<tr class='row".$feeder->id."'>\n";
			$output .= "		<td>\n";
			$output .= $i;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: left;'>\n";
			$output .= "<a href='".$link."'>".$feeder->iata."</a> ";
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: left;'>\n";
			$output .= $feeder->name;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: right;'>\n";
			$output .= number_format($feeder->markup, 0, ',', '.')." &euro;";
			$output .=  "		</td>\n";
			$output .=  "	</tr>\n";
		}

		$output .= "</table>\n";
		$output .= '</div>';
		
		return $output;
	 }

}
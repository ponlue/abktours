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
class TravelbooksViewFeeders extends JView
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


		$feeder	=& $this->get('feeder');
		$isNew        = ($feeder->id < 1);
		
// Title and Ikons
		$history = Jrequest::getVar('history','feeders.cancel');
		$task = JRequest::getCmd('task', 'travelbooks.show');
		switch ($task) {
			case 'feeders.show':
					JToolBarHelper::title(   JText::_( 'TB Feeder' ).' <small><small>[ ' .JText::_( 'TB List' ).' ]</small></small>' , 'feeder');
					JToolBarHelper::publish( 'feeders.publish', 'Publish' );
					JToolBarHelper::unpublish( 'feeders.unpublish', 'Unpublish' );
					JToolBarHelper::deleteListX( JText::_( 'TB Are You sure?' ),'feeders.delete', 'Delete' );
					JToolBarHelper::editListX( 'feeders.edit', 'Edit' );
					JToolBarHelper::addNewX( 'feeders.add', 'New' );
				break;
			case 'feeders.detail':
					JToolBarHelper::title(   JText::_( 'TB Feeder' ).' <small><small>[ ' .JText::_( 'TB Details' ).' ]</small></small>' , 'feeder');
					JToolBarHelper::save( 'feeders.save', 'Save' );
					JToolBarHelper::apply( 'feeders.apply', 'Apply' );
					JToolBarHelper::cancel( $history, 'Cancel' );
				break;
			case 'feeders.add':
					JToolBarHelper::title(   JText::_( 'TB Feeder' ).' <small><small>[ ' .JText::_( 'TB Add new Feeder' ).' ]</small></small>' , 'feeder');
					JToolBarHelper::save( 'feeders.save', 'Save' );
					JToolBarHelper::apply( 'feeders.apply', 'Apply' );
					JToolBarHelper::cancel( 'feeders.cancel', 'Cancel' );
				break;
		}

// Filters and co
		global $mainframe, $option_feeder, $orderby, $where, $pageNav;
	
		$filter_order		= $mainframe->getUserStateFromRequest( $option_feeder.'filter_order', 		'filter_order', 	'feeders.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option_feeder.'filter_order_Dir',	'filter_order_Dir',	'',					'word' );
		$filter_state 		= $mainframe->getUserStateFromRequest( $option_feeder.'filter_state', 		'filter_state', 	'',					'word' );
		$search 			= $mainframe->getUserStateFromRequest( $option_feeder.'search', 			'search', 			'',					'string' );
		$search 			= JString::strtolower( $search );
	
		$limit				= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart			= $mainframe->getUserStateFromRequest($option_feeder.'.limitstart', 'limitstart', 0, 'int');
	
		$where = array();
	
		if ( $search ) {
			$where[] = 'feeders.name LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ).' OR feeders.iata LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'feeders.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'feeders.published = 0';
			}
		}
	
		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		if ($filter_order == 'feeders.ordering'){
			$orderby 	= ' ORDER BY feeders.ordering';
		} else {
			$orderby 	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir .', feeders.ordering';
		}
	
// get the total number of records
		$total	=& $this->get('total');
		$this->assignRef('total', $total);	

		jimport('joomla.html.pagination');

		$pageNav = new JPagination( $total, $limitstart, $limit );

// get the subset (based on limits) of required records
		$feeders	=& $this->get('subset');
		$this->assignRef('feeders', $feeders);	

// get the subset (based on limits) of required records
//		$feeder	=& $this->get('feeder');
		$this->assignRef('feeder', $feeder);	

// state filter
		$lists['state']	= JHTML::_('grid.state',  $filter_state );
// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
// search filter
		$lists['search']= $search;
		$this->assignRef('lists',		$lists);	

// Details
// build the html radio buttons for published
	$details['published'] = JHTML::_('select.booleanlist',  'published', '', $feeder->published );
// build the html select list for ordering
	$query = 'SELECT ordering AS value, name AS text'
	. ' FROM #__tb_feeder'
	. ' WHERE published >= 0'
	. ' ORDER BY ordering'
	;
	$details['ordering'] 			= JHTML::_('list.specificordering',  $feeder, $feeder->id, $query );

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
	
}
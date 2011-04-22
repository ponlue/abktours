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
class TravelbooksViewSeasons_Feeders extends JView
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

// get the season
		$season	=& $this->get('season');

// set the icons
		JToolBarHelper::title(   JText::_( 'TB Seasons' ).' <small><small>[ ' .JText::sprintf( 'Add new Feeders to',$season->title.' : '.$season->departure.' - '.$season->arrival ).' ]</small></small>' , 'season');
		JToolBarHelper::addNewX( 'seasons.savefeeders', 'Add' );
		JToolBarHelper::deleteListX( JText::_( 'TB sure' ),'seasons.deletefeeders', 'Delete' );
		JToolBarHelper::cancel( 'seasons.cancelfeeders', 'Cancel' );
		JToolBarHelper::back( 'Back' );

// Filters and co
		global $mainframe, $option;
		global $orderby,		$where,		$pageNav;
		global $orderby_inv,	$where_inv,	$pageNav_inv;

		$filter_order			= $mainframe->getUserStateFromRequest( $option.'filter_order', 			'filter_order', 		'season_feeders.ordering',		'cmd' );
		$filter_order_Dir		= $mainframe->getUserStateFromRequest( $option.'filter_order_Dir',		'filter_order_Dir',		'',								'word' );

		$filter_state 			= $mainframe->getUserStateFromRequest( $option.'filter_state', 			'filter_state', 		'',								'word' );

		$search 				= $mainframe->getUserStateFromRequest( $option.'search', 					'search', 				'',								'string' );
		$search 				= JString::strtolower( $search );

		$limit					= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart				= $mainframe->getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');
		$limit_inv				= $mainframe->getUserStateFromRequest('global.list.limit_inv', 'limit_inv', $mainframe->getCfg('list_limit_inv'), 'int');
		$limitstart_inv			= $mainframe->getUserStateFromRequest($option.'.limitstart_inv', 'limitstart_inv', 0, 'int');

		$where 		= array();
		$where_inv	= array();

		$where[] = '(#__tb_dat_prt.dat_id)='.$season->id;
		$where_inv[] = '(((#__tb_feeder.id) Not In (SELECT prt_id FROM #__tb_dat_prt WHERE dat_id='.$season->id.')))';

		if ( $search<>'' ) {
			$where[] = '#__tb_feeder.name LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			$where_inv[] = '#__tb_feeder.name LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		if ( $filter_state<>'' ) {
			if ( $filter_state == 'P' ) {
				$where[] = '#__tb_feeder.published = 1';
				$where_inv[] = '#__tb_feeder.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = '#__tb_feeder.published = 0';
				$where_inv[] = '#__tb_feeder.published = 0';
			}
		}

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		$where_inv	= ( count( $where_inv ) ? ' WHERE ' . implode( ' AND ', $where_inv ) : '' );

		if ($filter_order == 'season_feeders.ordering'){
			$orderby 	= ' ORDER BY name';
			$orderby_inv 	= ' ORDER BY name';
		} else {
			$orderby 	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir;
			$orderby_inv 	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir;
		}
	
// get the total number of records
		$total		=& $this->get('total');
		$this->assignRef('total',		$total);	

		$total_inv	=& $this->get('total_inv');
		$this->assignRef('total_inv',	$total_inv);	

		jimport('joomla.html.pagination');
		$pageNav 		= new JPagination( $total, $limitstart, $limit );
		$pageNav_inv	= new JPagination( $total_inv, $limitstart_inv, $limit_inv );

// get the subset (based on limits) and its inverse of required records
		$feeders		=& $this->get('subset');
		$this->assignRef('feeders', 		$feeders);	
		$feeders_inv	=& $this->get('subset_inv');
		$this->assignRef('feeders_inv',	$feeders_inv);	
		$season	=& $this->get('season');
		$this->assignRef('season', $season);	

// state filter
		$lists['state']		= JHTML::_('grid.state',  $filter_state );
// table ordering
		$lists['order_Dir']		= $filter_order_Dir;
		$lists['order']			= $filter_order;
// search filter
		$lists['search']= $search;
		$this->assignRef('lists',		$lists);	

// Details
// build the html radio buttons for published
	$details['published'] = JHTML::_('select.booleanlist',  'published', '', $feeders->published );

	parent::display($tpl);

	}	 	
}
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
class TravelbooksViewCategories extends JView
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

		$category	=& $this->get('category');
		$isNew        = ($category->id < 1);
		
// Title and Ikons
		$history = Jrequest::getVar('history','categories.cancel');
		$task = JRequest::getCmd('task', 'travelbooks.show');
		switch ($task) {
			case 'categories.show':
					JToolBarHelper::title(   JText::_( 'TB Categories' ).' <small><small>[ ' .JText::_( 'TB List' ).' ]</small></small>' , 'category');
					JToolBarHelper::publish( 'categories.publish', 'Publish' );
					JToolBarHelper::unpublish( 'categories.unpublish', 'Unpublish' );
					JToolBarHelper::deleteListX( JText::_( 'TB Are You sure?' ),'categories.delete', 'Delete' );
					JToolBarHelper::editListX( 'categories.edit', 'Edit' );
					JToolBarHelper::addNewX( 'categories.add', 'New' );
				break;
			case 'categories.detail':
					JToolBarHelper::title(   JText::_( 'TB Categories' ).' <small><small>[ ' .JText::_( 'TB Details' ).' ]</small></small>' , 'category');
					JToolBarHelper::save( 'categories.save', 'Save' );
					JToolBarHelper::apply( 'categories.apply', 'Apply' );
					JToolBarHelper::cancel( $history, 'Cancel' );
				break;
			case 'categories.add':
					JToolBarHelper::title(   JText::_( 'TB Categories' ).' <small><small>[ ' .JText::_( 'TB Add new Category' ).' ]</small></small>' , 'category');
					JToolBarHelper::save( 'categories.save', 'Save' );
					JToolBarHelper::apply( 'categories.apply', 'Apply' );
					JToolBarHelper::cancel( 'categories.cancel', 'Cancel' );
				break;
		}

// Filters and co
		global $mainframe, $option_category, $orderby, $where, $pageNav;
	
		$filter_order		= $mainframe->getUserStateFromRequest( $option_category.'filter_order', 		'filter_order', 	'categories.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option_category.'filter_order_Dir',	'filter_order_Dir',	'',					'word' );
		$filter_state 		= $mainframe->getUserStateFromRequest( $option_category.'filter_state', 		'filter_state', 	'',					'word' );

		$filter_sections	= $mainframe->getUserStateFromRequest( $option_category.'filter_sections', 		'filter_sections',	'',					'word' );

		$search 			= $mainframe->getUserStateFromRequest( $option_category.'search', 			'search', 			'',					'string' );
		$search 			= JString::strtolower( $search );
	
		$limit				= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart			= $mainframe->getUserStateFromRequest($option_category.'.limitstart', 'limitstart', 0, 'int');

		$where = array();
		
		$where[] = '( categories.section = "com_hotel_details" OR categories.section = "com_service_details" OR categories.section = "com_tour_details" )';
	
		if ( $search ) {
			$where[] = 'categories.title LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		if ( $filter_sections ) {
			$where[] = 'categories.section LIKE '.$db->Quote( '%'.$db->getEscaped( $filter_sections, true ).'%', false );
		}

		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'categories.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'categories.published = 0';
			}
		}
	
		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		if ($filter_order == 'categories.ordering'){
			$orderby 	= ' ORDER BY categories.ordering';
		} else {
			$orderby 	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir .', categories.ordering';
		}
	
// get the total number of records
		$total	=& $this->get('total');
		$this->assignRef('total', $total);	

		jimport('joomla.html.pagination');

		$pageNav = new JPagination( $total, $limitstart, $limit );

// get the subset (based on limits) of required records
		$categories	=& $this->get('subset');
		$this->assignRef('categories', $categories);	

// get the subset (based on limits) of required records
		$category	=& $this->get('category');
		$this->assignRef('category', $category);	

// build list of categories
		$javascript = 'onchange="document.adminForm.submit();"';
		$lists['catid'] = JHTML::_('list.category',  'filter_catid', 'com_category_details', intval( $filter_catid ), $javascript );
// state filter
		$lists['state']	= JHTML::_('grid.state',  $filter_state );
// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
// search filter
		$lists['search']= $search;
//		$this->assignRef('lists',		$lists);	

// Details
// build list of sections
	$sections = array();
	$sections[] = JHTML::_('select.option',  0, JText::_('TB Section Selection') );
	$sections[] = JHTML::_('select.option', 'com_hotel_details',JText::_('com_hotel_details') );
	$sections[] = JHTML::_('select.option', 'com_service_details', JText::_('com_service_details') );
	$sections[] = JHTML::_('select.option', 'com_tour_details', JText::_('com_tour_details') );
	
	$slist = JHTML::_('select.genericlist', $sections, 'filter_sections', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $filter_sections );
	$lists['sections'] = $slist;
	$slist = JHTML::_('select.genericlist', $sections, 'section', 'class="inputbox" size="1" ', 'value', 'text', $category->section );
	$lists['slist'] = $slist;
	$this->assignRef('lists',		$lists);	
	
// build the html radio buttons for published
	$details['published'] = JHTML::_('select.booleanlist',  'published', '', $category->published );

	$this->assignRef('details',		$details);	

	parent::display($tpl);
		
	}
	
}
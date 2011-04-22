<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: travelbooks.php 2 2010-04-13 13:37:46Z WEB $
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

jimport( 'joomla.application.component.model' );

/**
 * Travelbook Model
 *
 * @package    Travelbook
 * @subpackage administrator
 */
class TravelbooksModelTravelbooks extends JModel
{
	/**
	 * Travelbooks data array
	 *
	 * @var array
	 */
	var $_data;

	/**
	 * Get the list of published tabs, based on the ID
	 */

	function getPublishedTabs() {

		$tabs = array();

		$pane = new stdClass();
		$pane->title = 'TB Tours';
		$pane->name = 'tours';
		$pane->alert = false;
		$tabs[] = $pane;

// Validating other tabs based on extension configuration
		$params = JComponentHelper::getParams('com_Travelbook');
		if( $params->get('showPanelNews', 1) ) {
			$pane = new stdClass();
			$pane->title = 'TB News';
			$pane->name = 'news';
			$pane->alert = false;
			$tabs[] = $pane;
		}
		if( $params->get('showPanelNews', 1) ) {
			$pane = new stdClass();
			$pane->title = 'TB TravelbookNews';
			$pane->name = 'travelbookNews';
			$pane->alert = false;
			$tabs[] = $pane;
		}

		return $tabs;
	}

	/**
	* List the TOUR records
	* @param string The current GET/POST option
	*/
	function &gettours()
	{	
	// get the subset (based on limits) of required records
		$query = 'SELECT #__tb_journey.id, #__tb_journey.title, #__tb_journey.year, #__tb_journey.published, #__tb_journey.ordering, #__categories.title AS category'
		. ' FROM #__categories INNER JOIN #__tb_journey ON #__categories.id = #__tb_journey.catid;'
		;
		$this->_db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$this->_list = $this->_db->loadObjectList();

		return $this->_list;
	}

}
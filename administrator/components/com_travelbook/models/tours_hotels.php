<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: tours_hotels.php 2 2010-04-13 13:37:46Z WEB $
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

jimport('joomla.application.component.model');

/**
 * Travelbook Model
 *
 * @package    demoa-page.Travelbook
 * @subpackage Components
 */

class TravelbooksModelTours_Hotels extends JModel
{
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('tid',  0, '', 'array');
		$this->setTId((int)$array[0]);
	}

	/**
	 * Method to set the Hotel identifier
	 *
	 * @access	public
	 * @param	int Hotel identifier
	 * @return	void
	 */
	function setTId($id)
	{
		// Set id and wipe data
		if ($id == 0) {
			$this->_tid		= 0;
		} else {
			$this->_tid		= $id;
		}
		$this->_data	= null;	
	}

/**
 * Method to get the tour data
 * @return object with data
 */
	function &gettour()
	{
// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__tb_journey '.
					 ' WHERE id = "'.$this->_tid.'";';
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}

		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
		}

		return $this->_data;
	}

/**
 * Method to get number of hotels already linked to a tour
 * @return object with data
 */
	function &gettotal()
	{
		$query = ' SELECT Count(#__tb_jrn_htl.id)'.
				 ' FROM #__tb_jrn_htl'.
				 ' WHERE jrn_id = "'.$this->_tid.'";';
		$this->_db->setQuery( $query );
		$this->_total = $this->_db->loadResult();
		
		return $this->_total;
	}

/**
 * Method to get number of hotels not yet linked to a tour
 * @return object with data
 */
	function &gettotal_inv()
	{
		$query = ' SELECT Count(#__tb_hotels.id)'.
				 ' FROM (#__categories INNER JOIN #__tb_hotels ON #__categories.id = #__tb_hotels.catid) LEFT JOIN #__tb_jrn_htl ON #__tb_hotels.id = #__tb_jrn_htl.htl_id '.
				 ' WHERE (((#__tb_hotels.id) Not In (SELECT htl_id FROM #__tb_jrn_htl WHERE jrn_id ="'.$this->_tid.'")));';
		$this->_db->setQuery( $query );
		$this->_total = $this->_db->loadResult();

		return $this->_total;
	}

/**
 * Method to get all hotels already linked to a tour
 * @return object with data
 */
	function &getsubset()
	{
	global $orderby, $where, $pageNav;
		$query = ' SELECT #__tb_hotels.name, #__categories.title AS category, #__tb_hotels.published, #__tb_jrn_htl.accomodation, #__tb_jrn_htl.rate, #__tb_jrn_htl.htl_id, #__tb_jrn_htl.id'
		. ' FROM #__tb_jrn_htl LEFT JOIN (#__tb_hotels LEFT JOIN #__categories ON #__tb_hotels.catid = #__categories.id) ON #__tb_jrn_htl.htl_id = #__tb_hotels.id'
		. $where
		. $orderby
		;
		$this->_db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$this->_list = $this->_db->loadObjectList();

		return $this->_list;
	}

/**
 * Method to get all hotels not yet linked to a tour
 * @return object with data
 */
	function &getsubset_inv()
	{
	global $orderby_inv, $where_inv, $pageNav_inv;
		$query = ' SELECT #__tb_hotels.id, #__tb_hotels.name, #__tb_hotels.published, #__tb_hotels.catid, #__categories.title AS category'
//		. ' FROM (#__categories INNER JOIN #__tb_hotels ON #__categories.id = #__tb_hotels.catid) LEFT JOIN #__tb_jrn_htl ON #__tb_hotels.id = #__tb_jrn_htl.htl_id'
		. ' FROM #__categories AS #__categories_1 INNER JOIN (#__categories INNER JOIN #__tb_hotels ON #__categories.id = #__tb_hotels.catid) ON #__categories_1.id = #__tb_hotels.catid'
		. $where_inv
		. $orderby_inv
		;
		$this->_db->setQuery( $query, $pageNav_inv->limitstart_inv, $pageNav_inv->limit );
		$this->_list = $this->_db->loadObjectList();

		return $this->_list;
	}

	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store($data)
	{	

		$row =& JTable::getInstance('tours_hotels', 'Table');

		// Bind the form fields to the hotel table
		if (!$row->bind($data)) {
	        return JError::raiseWarning( 500, $row->getError() );
		}
		// Make sure the hotel record is valid
		if (!$row->check()) {
	        return JError::raiseError(500, $row->getError() );
		}

		// Store the web link table to the database
		if (!$row->store()) {
	        return JError::raiseError(500, $row->getError() );
		}

		return true;
	}

	/**
	 * Method to delete record(s)
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete()
	{

		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$row =& JTable::getInstance('tours_hotels', 'Table');

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}

/**
 * Get the list of published tabs, based on the ID
 *
 * @access	public
 * @return	tabs
 */
	function getPublishedTabs()
	{
		$tabs = array();

		$pane = new stdClass();
		$pane->title = 'TB Tours';
		$pane->name = 'tours';
		$pane->alert = false;
		$tabs[] = $pane;
		
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
		. ' FROM #__categories INNER JOIN (#__tb_journey INNER JOIN #__tb_jrn_htl ON #__tb_journey.id = #__tb_jrn_htl.jrn_id) ON #__categories.id = #__tb_journey.catid'
		. ' WHERE (((#__tb_jrn_htl.htl_id)= '.$this->_tid.'));' 
		;

		$this->_db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$this->_list = $this->_db->loadObjectList();

		return $this->_list;
	}

}
<?php
/**
 * "TRAVELbook - JOOMLA! on Tour" - http://www.demo-page.de
 *
 * @version         $Id: admin models seasons_feeders.php 001 2010-01-22 12:00:00$
 * @copyright       Copyright 2009-2010, Peter H&ouml;cherl
 * @license         GNU General Public License (GNU GPL) GPLv2, 
 *                  - see http://www.demo-page.de/en/license-conditions.html *
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
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

class TravelbooksModelSeasons_Feeders extends JModel
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
	 * Method to set the Feeder identifier
	 *
	 * @access	public
	 * @param	int Feeder identifier
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
 * Method to get the season data
 * @return object with data
 */
	function &getseason()
	{
// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT #__tb_journey.title, #__tb_season.*'.
					 ' FROM #__tb_season INNER JOIN #__tb_journey ON #__tb_season.jrn_id = #__tb_journey.id'.
					 ' WHERE #__tb_season.id = "'.$this->_tid.'";';
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
 * Method to get number of feeders already linked to a season
 * @return object with data
 */
	function &gettotal()
	{
		$query = ' SELECT Count(id)'.
				 ' FROM #__tb_dat_prt'.
				 ' WHERE dat_id = "'.$this->_tid.'";';
		$this->_db->setQuery( $query );
		$this->_total = $this->_db->loadResult();

		return $this->_total;
	}

/**
 * Method to get number of feeders not yet linked to a season
 * @return object with data
 */
	function &gettotal_inv()
	{
		$query = ' SELECT Count(#__tb_jrn_srv.id)'.
				 ' FROM #__tb_jrn_srv'.
				 ' WHERE jrn_id <> "'.$this->_tid.'";';
		$this->_db->setQuery( $query );
		$this->_total = $this->_db->loadResult();
		
		return $this->_total;
	}

/**
 * Method to get all feeders already linked to a season
 * @return object with data
 */
	function &getsubset()
	{
	global $orderby, $where, $pageNav;
		$query = ' SELECT #__tb_dat_prt.id, #__tb_dat_prt.prt_id, #__tb_dat_prt.dat_id, #__tb_dat_prt.markup, #__tb_feeder.name, #__tb_feeder.iata, #__tb_feeder.published'
		. ' FROM #__tb_dat_prt INNER JOIN #__tb_feeder ON #__tb_dat_prt.prt_id = #__tb_feeder.id'
		. $where
		. $orderby
		;
		$this->_db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$this->_list = $this->_db->loadObjectList();

		return $this->_list;
	}

/**
 * Method to get all feeders not yet linked to a season
 * @return object with data
 */
	function &getsubset_inv()
	{
	global $orderby_inv, $where_inv, $pageNav_inv;
		$query = ' SELECT #__tb_feeder.id, #__tb_feeder.name, #__tb_feeder.iata, #__tb_feeder.published'
		. ' FROM #__tb_feeder'
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

		$row =& JTable::getInstance('seasons_feeders', 'Table');
		// Bind the form fields to the feeder table
		if (!$row->bind($data)) {
	        return JError::raiseWarning( 500, $row->getError() );
		}
		// Make sure the feeder record is valid
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
		$row =& JTable::getInstance('seasons_feeders', 'Table');

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
		$pane->title = 'TB Seasons';
		$pane->name = 'seasons';
		$pane->alert = false;
		$tabs[] = $pane;
		
		return $tabs;
	}

	/**
	* List the TOUR records
	* @param string The current GET/POST option
	*/
	function &getseasons()
	{	
	// get the subset (based on limits) of required records
		$query = 'SELECT #__tb_journey.id, #__tb_journey.title, #__tb_journey.year, #__tb_journey.published, #__tb_journey.ordering, #__categories.title AS category'
		. ' FROM #__categories INNER JOIN (#__tb_journey INNER JOIN #__tb_jrn_srv ON #__tb_journey.id = #__tb_jrn_srv.jrn_id) ON #__categories.id = #__tb_journey.catid'
		. ' WHERE (((#__tb_jrn_srv.srv_id)= '.$this->_tid.'));' 
		;

		$this->_db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$this->_list = $this->_db->loadObjectList();

		return $this->_list;
	}

}
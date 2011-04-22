<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: tours.php 2 2010-04-13 13:37:46Z WEB $
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

class TravelbooksModelTours extends JModel
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
	 * Method to set the Tour identifier
	 *
	 * @access	public
	 * @param	int Tour identifier
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
	 * Method to get a travelbook
	 * @return object with data
	 */
	function &gettour()
	{
// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__tb_journey '.
					' WHERE id = "'.$this->_tid.'" ;';
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
	* List the TOUR records
	* @param string The current GET/POST option
	*/
	function &gettotal()
	{	
// get the total number of records
	$query = 'SELECT COUNT(*)'
	. ' FROM #__tb_journey AS tours'
	. $where
	;
	$this->_db->setQuery( $query );
	$this->_total = $this->_db->loadResult();
	
	return $this->_total;
	}

	/**
	* List the TOUR records
	* @param string The current GET/POST option
	*/
	function &getsubset()
	{	
	global $orderby, $where, $pageNav;
	// get the subset (based on limits) of required records
		$query = 'SELECT tours.*, cc.title AS category'
		. ' FROM #__tb_journey AS tours'
		. ' LEFT JOIN #__categories AS cc ON cc.id = tours.catid'
		. $where
		. $orderby
		;
		$this->_db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$this->_list = $this->_db->loadObjectList();

		return $this->_list;
	}

/**
* Changes the state of one or more content pages
* @param array An array of unique category id numbers
* @param integer 0 if unpublishing, 1 if publishing
* @param string The current option
*/
function &changeTours( $cid=null, $state=0 )
{
	global $mainframe;

	// Check for request forgeries
	JRequest::checkToken() or jexit( 'Invalid Token' );

	// Initialize variables
	$db 	=& JFactory::getDBO();
//	$user 	=& JFactory::getUser();
	JArrayHelper::toInteger($cid);

	if (count( $cid ) < 1) {
		$action = $state ? 'publish' : 'unpublish';
		JError::raiseError(500, JText::_( 'Select an item to' .$action, true ) );
	}

	$cids = implode( ',', $cid );

	$query = 'UPDATE #__tb_journey'
	. ' SET published = ' . (int) $state
	. ' WHERE id IN ( '. $cids .' )'
//	. ' AND ( checked_out = 0 OR ( checked_out = '. (int) $user->get('id') .' ) )'
	;
	$db->setQuery( $query );
	if (!$db->query()) {
		JError::raiseError(500, $db->getErrorMsg() );
	}

	if (count( $cid ) == 1) {
		$row =& JTable::getInstance('tour', 'Table');
		$row->checkin( intval( $cid[0] ) );
	}

	$mainframe->redirect( 'index.php?option=com_travelbook&task=tours.show' );
}

/** JJC
* Moves the order of a record
* @param integer The increment to reorder by
*/
function orderTours( $uid, $inc )
{
	global $mainframe;
	// Check for request forgeries
	JRequest::checkToken() or jexit( 'Invalid Token' );

	// Initialize variables
	$db =& JFactory::getDBO();

	$row =& JTable::getInstance('tour', 'Table');
	$row->load( $uid );

//	$row->move( $inc, 'catid = '. (int) $row->catid .' AND published != 0' );
	$row->move( $inc, 'published != 0' );

//	$msg 	= 'New ordering saved';
	$mainframe->redirect( 'index.php?option=com_travelbook&task=tours.show', $msg );
}

function saveOrder( &$cid )
{
	global $mainframe;
	// Check for request forgeries
	JRequest::checkToken() or jexit( 'Invalid Token' );

	// Initialize variables
	$db			=& JFactory::getDBO();
	$total		= count( $cid );
	$order 		= JRequest::getVar( 'order', array(0), 'post', 'array' );
	JArrayHelper::toInteger($order, array(0));

	$row =& JTable::getInstance('tour', 'Table');
//	$groupings = array();

	// update ordering values
	for( $i=0; $i < $total; $i++ ) {
		$row->load( (int) $cid[$i] );
		// track categories
//		$groupings[] = $row->catid;

		if ($row->ordering != $order[$i]) {
			$row->ordering = $order[$i];
			if (!$row->store()) {
				JError::raiseError(500, $db->getErrorMsg() );
			}
		}
	}

	// execute updateOrder for each parent group
//	$groupings = array_unique( $groupings );
//	foreach ($groupings as $group){
//		$row->reorder('catid = '.(int) $group);
//	}

	$msg 	= 'New ordering saved';
	$mainframe->redirect( 'index.php?option=com_travelbook&task=tours.show', $msg );
//	$mainframe->redirect( 'index.php?option=com_travelbook', $msg );
}


	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store($data)
	{	
		// Change DateFormat to MySQL
		$arr_date =& JFactory::getDate( $data['arrival'], 0 );
		$data['arrival'] = $arr_date->toMySQL();
		$dep_date =& JFactory::getDate( $data['departure'], 0 );
		$data['departure'] = $dep_date->toMySQL();

		$row =& JTable::getInstance('tour', 'Table');

		// Bind the form fields to the tour table
		if (!$row->bind($data)) {
	        return JError::raiseWarning( 500, $row->getError() );
		}

		// Make sure the tour record is valid
		if (!$row->check()) {
	        return JError::raiseError(500, $row->getError() );
		}

		// Store the web link table to the database
		if (!$row->store()) {
	        return JError::raiseError(500, $row->getError() );
		}

		global $new_id;
		$new_id = $row->id;

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

		$tids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$row =& JTable::getInstance('tour', 'Table');

		if (count( $tids )) {
			foreach($tids as $tid) {
				if (!$row->delete( $tid )) {
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

		switch ($this->_state->task) {
			case 'addseason':
				$pane = new stdClass();
				$pane->title = 'TB Seasons';
				$pane->name = 'seasons';
				$pane->alert = false;
				$tabs[] = $pane;
		
				break;
			case 'addservices':
				$pane = new stdClass();
				$pane->title = 'TB Services';
				$pane->name = 'services';
				$pane->alert = false;
				$tabs[] = $pane;
		
				break;
		   default:
				$pane = new stdClass();
				$pane->title = 'TB Seasons';
				$pane->name = 'seasons';
				$pane->alert = false;
				$tabs[] = $pane;
		
				$pane = new stdClass();
				$pane->title = 'TB Services';
				$pane->name = 'services';
				$pane->alert = false;
				$tabs[] = $pane;
				
				$pane = new stdClass();
				$pane->title = 'TB Accomodation';
				$pane->name = 'accomodation';
				$pane->alert = false;
				$tabs[] = $pane;

				break;
		}
		
		
		return $tabs;
	}

	/**
	* List the TOUR records
	* @param string The current GET/POST option
	*/
	function &getseasons()
	{	
	// get the subset (based on limits) of required records
		$query = 'SELECT season.*'
		. ' FROM #__tb_season AS season'
		. ' WHERE season.jrn_id = '.$this->_tid 
		;
		$this->_db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$this->_list = $this->_db->loadObjectList();

		return $this->_list;
	}

	/**
	* List the TOUR records
	* @param string The current GET/POST option
	*/
	function &getservices()
	{	
	// get the subset (based on limits) of required records
		$query = 'SELECT #__categories.title As title, #__categories.ordering, #__categories_1.title AS category, #__tb_services.id, #__tb_services.name, #__tb_services.rate, #__tb_jrn_srv.ordering'
		.' FROM #__tb_jrn_srv INNER JOIN ((#__tb_services INNER JOIN #__categories ON #__tb_services.catid = #__categories.id) INNER JOIN #__categories AS #__categories_1 ON #__tb_services.srv_type = #__categories_1.id) ON #__tb_jrn_srv.srv_id = #__tb_services.id'
		.' WHERE (((#__tb_jrn_srv.jrn_id)='.$this->_tid.'))'
		.' ORDER BY #__categories.ordering, #__tb_jrn_srv.ordering;' 
		;

		$this->_db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$this->_list = $this->_db->loadObjectList();

		return $this->_list;
	}
	/**
	* List the TOUR records
	* @param string The current GET/POST option
	*/
	function &getservices_inv()
	{	
	// get the subset (based on limits) of required records
		$query = 'SELECT #__categories.title AS categroy, #__categories_1.title AS type, #__tb_services.id, #__tb_services.name, #__tb_services.rate, #__tb_jrn_srv.jrn_id, #__tb_jrn_srv.ordering'
		.' FROM #__tb_jrn_srv LEFT JOIN ((#__tb_services LEFT JOIN #__categories ON #__tb_services.catid = #__categories.id) LEFT JOIN #__categories AS #__categories_1 ON #__tb_services.srv_type = #__categories_1.id) ON #__tb_jrn_srv.srv_id = #__tb_services.id'
		.' WHERE (((#__tb_services.id)>0) AND ((#__tb_jrn_srv.jrn_id)<>'.$this->_tid.'))'
		.' ORDER BY #__tb_jrn_srv.ordering;' 
		;

		$this->_db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$this->_list = $this->_db->loadObjectList();

		return $this->_list;
	}

	/**
	* List the TOUR records
	* @param string The current GET/POST option
	*/
	function &gethotels()
	{	
	// get the subset (based on limits) of required records
		$query = 'SELECT #__categories.title, #__tb_hotels.name, #__tb_hotels.city, #__tb_hotels.id, #__tb_jrn_htl.accomodation, #__tb_jrn_htl.ordering, #__tb_hotels.rate, #__tb_hotels.chain'
		.' FROM #__tb_jrn_htl INNER JOIN (#__tb_hotels INNER JOIN #__categories ON #__tb_hotels.catid = #__categories.id) ON #__tb_jrn_htl.htl_id = #__tb_hotels.id'
		.' WHERE (((#__tb_jrn_htl.jrn_id)='.$this->_tid.'))'
		.' ORDER BY #__tb_jrn_htl.ordering;' 
		;

		$this->_db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$this->_list = $this->_db->loadObjectList();

		return $this->_list;
	}

}
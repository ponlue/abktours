<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: mails.php 2 2010-04-13 13:37:46Z WEB $
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

class TravelbooksModelMails extends JModel
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
	 * Method to set the Mail identifier
	 *
	 * @access	public
	 * @param	int Mail identifier
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
	function &getmail()
	{
// Load the data

		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__tb_mails '.
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
	. ' FROM #__tb_mails AS mails'
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
	global $where, $pageNav;
	// get the subset (based on limits) of required records
		$query = 'SELECT mails.*'
		. ' FROM #__tb_mails AS mails'
		. $where
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
function &changeMails( $cid=null, $state=0 )
{
	global $mainframe;

	// Check for request forgeries
	JRequest::checkToken() or jexit( 'Invalid Token' );

	// Initialize variables
	$db 	=& JFactory::getDBO();
	JArrayHelper::toInteger($cid);

	if (count( $cid ) < 1) {
		$action = $state ? 'publish' : 'unpublish';
		JError::raiseError(500, JText::_( 'Select an item to' .$action, true ) );
	}

	$cids = implode( ',', $cid );

	$query = 'UPDATE #__tb_mails'
	. ' SET published = ' . (int) $state
	. ' WHERE id IN ( '. $cids .' )'
//	. ' AND ( checked_out = 0 OR ( checked_out = '. (int) $user->get('id') .' ) )'
	;
	$db->setQuery( $query );

	if (!$db->query()) {
		JError::raiseError(500, $db->getErrorMsg() );
	}

	if (count( $cid ) == 1) {
		$row =& JTable::getInstance('mail', 'Table');
		$row->checkin( intval( $cid[0] ) );
	}

	$mainframe->redirect( 'index.php?option=com_travelbook&task=mails.show' );
}

/**
* Moves the order of a record
* @param integer The increment to reorder by
*/
function orderMails( $uid, $inc )
{
	global $mainframe;

	// Check for request forgeries
	JRequest::checkToken() or jexit( 'Invalid Token' );

	// Initialize variables
	$db =& JFactory::getDBO();

	$row =& JTable::getInstance('mail', 'Table');
	$row->load( $uid );

	$row->move( $inc, 'state != 0' );

	$mainframe->redirect( 'index.php?option=com_travelbook&task=mails.show', $msg );
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

	$row =& JTable::getInstance('mail', 'Table');

	// update ordering values
	for( $i=0; $i < $total; $i++ ) {
		$row->load( (int) $cid[$i] );

		if ($row->ordering != $order[$i]) {
			$row->ordering = $order[$i];
			if (!$row->store()) {
				JError::raiseError(500, $db->getErrorMsg() );
			}
		}
	}


	$msg 	= 'New ordering saved';
	$mainframe->redirect( 'index.php?option=com_travelbook&task=mails.show', $msg );
}


	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store($data)
	{	

		$row =& JTable::getInstance('mail', 'Table');

		// Bind the form fields to the mail table
		if (!$row->bind($data)) {
	        return JError::raiseWarning( 500, $row->getError() );
		}

		// Make sure the mail record is valid
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
		$row =& JTable::getInstance('mail', 'Table');

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

	function saveContentPrep( &$row )
	{

		// Get submitted text from the request variables
		$text = JRequest::getVar( 'mailbody', '', 'post', 'string', JREQUEST_ALLOWRAW );

		// Clean text for xhtml transitional compliance
		$text		= str_replace( '<br>', '<br />', $text );

		$row->mailbody	= $text;
		$filter = new JFilterInput( array(), array(), 1, 1 );

		$row->mailbody	= $filter->clean( $row->mailbody );
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
		$pane->title = 'TB Support';
		$pane->name = 'support';
		$pane->alert = false;
		$tabs[] = $pane;
		
		return $tabs;
	}

}
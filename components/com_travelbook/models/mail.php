<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: mail.php 2 2010-04-13 13:37:46Z WEB $
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
 * Twitter Model
 *
 * @package    demoa-page.Twitter
 * @subpackage Components
 */
class TravelbookModelMail extends JModel
{
/**
 * Travelbook data array
 *
 * @var array
 */
	var $_mail;

	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();

		$this->_data	= null;	
	}

/**
 * Method to get a list with all published mail templates
 * @return object with data
 */
	function &getmail()
	{
// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__tb_mails'.
					' WHERE published != 0;';
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObjectList();
		}

		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
		}
		return $this->_data;
	}


/**
 * Method to store a record
 *
 * @access	public
 * @return	boolean	True on success
 */

	function store($data)
	{
		$row =& $this->getTable('mail');

/*** Bind the form fields to the TravelBook table ***/
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

/*** Create the timestamp for the travelbook ***/
		$row->timestamp = gmdate('Y-m-d H:i:s');

/*** Make sure the mail record is valid ***/
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

/*** Store the travelbook in our database ***/
		if (!$row->store()) {
	        $this->setError($this->_db->getErrorMsg());
			return false;
		}

		global $new_id_clt;
		$new_id_clt = $row->id;

		return true;
	}
	

	/**
	 * Method to delete an data field
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */

	function delete($cid)
	{	

		$db =& JFactory::getDBO();

		$row =& $this->getTable();

		// Make sure the TravelBook record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Delete the Datafield from the TravelBook table in the database
		if (!$row->delete($cid[0])) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}
	
	/**
	 * Method to checkin/unlock the weblink
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function checkin()
	{
		if ($this->_id)
		{
			$travelbook = & $this->getTable();
			if(! $travelbook->checkin($this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;
	}
}
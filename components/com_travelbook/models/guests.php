<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: guests.php 2 2010-04-13 13:37:46Z WEB $
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
 * Guest Model
 *
 * @package    demo-page.TRAVELbook
 * @subpackage Components
 */
class TravelbookModelGuests extends JModel
{
	/**
	 * Guest data array
	 *
	 * @var array
	 */
	var $_guest;

/**
 * Method to store a guest record
 *
 * @access	public
 * @return	boolean	True on success
 */

	function store($data,$id_clt)
	{

		$row =& $this->getTable('guests');
/*** Bind the form fields to the Guests table ***/
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

/*** Create the timestamp for the guests ***/
		$row->timestamp = gmdate('Y-m-d H:i:s');
		$row->id_clt = $id_clt;

/*** Make sure the guest record is valid ***/
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

/*** Store the travelbook in our database ***/
		if (!$row->store()) {
	        $this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

}
<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: hotel.php 2 2010-04-13 13:37:46Z WEB $
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

/**
 * Travelbook Table class
 *
 * @package    Travelbook
 * @subpackage administrator
 */
class TableHotel extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = 0;

	/**
	 * @var string
	 */
	var $name = '';
	var $catid = 0;
	var $city = '';
	var $published = 0;
	var $ordering = 0;
	var $rate = 0.00;
	var $chain = '';

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableHotel(& $db) {
		parent::__construct('#__tb_hotels', 'id', $db);
	}
}
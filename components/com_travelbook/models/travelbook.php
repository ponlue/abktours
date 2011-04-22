<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: travelbook.php 2 2010-04-13 13:37:46Z WEB $
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
class TravelbookModelTravelbook extends JModel
{
	/**
	 * Travelbook data array
	 *
	 * @var array
	 */
	var $_travelbook;

	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('tour',  0, '', 'array');
		$this->setTId((int)$array[0]);
		$array = JRequest::getVar('season',  0, '', 'array');
		$this->setDId((int)$array[0]);
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
			$this->_tid		= $this->getFirstTour();
		} else {
			$this->_tid		= $id;
		}

	}

	/**
	 * Method to set the Tour identifier
	 *
	 * @access	public
	 * @param	int Tour identifier
	 * @return	void
	 */
	function setDId($id)
	{
		// Set id and wipe data
		if ($id == 0) {
			$this->_did		= $this->getFirstDate();
		} else {
			$this->_did		= $this->getFirstDate();
//			$this->_did		= $id;
		}

	}

	/**
	 * Gets the first published tour
	 * @return data to be used later
	 */ 
	function getFirstTour()
	{
		$db =& JFactory::getDBO();
		
		$query = 	'SELECT id '.
					'FROM #__tb_journey '.
					'WHERE (((#__tb_journey.published)=1)) '.
					'ORDER BY #__tb_journey.ordering ASC;';
		$db->setQuery( $query );
		$tb_first_tour = $db->loadResult();

		return $tb_first_tour;
	}

	/**
	 * Gets the first published season
	 * @return data to be used later
	 */ 
	function getFirstDate()
	{
		$db =& JFactory::getDBO();

		$id_tour = $this->_tid;
		
		$query = 	'SELECT id '.
					'FROM #__tb_season '.
					'WHERE (((#__tb_season.published)=1) AND ((#__tb_season.jrn_id)='.$id_tour.')) '.
					'ORDER BY #__tb_season.ordering ASC;';
		$db->setQuery( $query );
		$tb_first_date = $db->loadResult();

		return $tb_first_date;
	}

	/**
	 * Gets all publishede tours
	 * @return data to be used later
	 */ 
	function getTBActiveTours()
	{
		$db =& JFactory::getDBO();

		$id_tour = $this->_tid;

		$query = 	'SELECT * '.
					'FROM #__tb_journey '.
					'WHERE (((#__tb_journey.published)=1)) '.
					'ORDER BY #__tb_journey.ordering ASC;';


		$db->setQuery( $query );
		$tb_published_tours = $db->loadObjectList();

		return $tb_published_tours;
	}

	/**
	 * Gets all data linked to a choosen (or default) tour
	 * @return data to be used later
	 */ 
	function getTBTour()
	{
		$db =& JFactory::getDBO();

		$id_tour = $this->_tid;

		$query = 	'SELECT #__tb_journey.id AS tour_id, #__tb_journey.title, #__tb_journey.picture, #__tb_journey.published, #__tb_journey.ordering, #__tb_journey.select_hotel, #__tb_season.id AS dat_id, #__categories.title AS category, #__tb_season.departure, #__tb_season.arrival, #__tb_season.rate, #__tb_season.single_supplement, #__tb_season.ordering, #__tb_dat_prt.markup '.
					'FROM (#__tb_journey LEFT JOIN (#__tb_feeder RIGHT JOIN (#__tb_season LEFT JOIN #__tb_dat_prt ON #__tb_season.id = #__tb_dat_prt.id) ON #__tb_feeder.id = #__tb_dat_prt.prt_id) ON #__tb_journey.id = #__tb_season.jrn_id) INNER JOIN #__categories ON #__tb_journey.catid = #__categories.id '.
					'WHERE (((#__tb_journey.published)="1") AND ((#__tb_season.published)="1") AND ((#__tb_journey.id)='.$id_tour.')) '.
					'ORDER BY #__tb_journey.ordering ASC, #__tb_season.ordering ASC, #__tb_feeder.id ASC;';

		$db->setQuery( $query );
//var_dump($db);
		$tb_tour = $db->loadObjectList();

		return $tb_tour;
	}

	/**
	 * Gets all Services linked to a choosen (or default) tour
	 * @return data to be used later
	 */ 
	 
	function getTBServices()
	{
		$db =& JFactory::getDBO();
		
		$id_tour = $this->_tid;

		$query = 	'SELECT #__tb_services.srv_type, #__categories.title, #__tb_services.checked, #__tb_services.pro_rata, #__tb_services.ordering, #__tb_services.published, #__tb_services.rate, #__categories_1.title, #__tb_services.catid, #__tb_services.name '.
					'FROM #__categories AS #__categories_1 INNER JOIN ((#__tb_services INNER JOIN #__categories ON #__tb_services.srv_type = #__categories.id) INNER JOIN (#__tb_journey INNER JOIN #__tb_jrn_srv ON #__tb_journey.id = #__tb_jrn_srv.jrn_id) ON #__tb_services.id = #__tb_jrn_srv.srv_id) ON #__categories_1.id = #__tb_services.catid '.
					'WHERE ((#__tb_journey.id) = '.$id_tour.' AND (#__tb_journey.published) = 1 AND (#__categories.title) = "inclusiv" ) '.
					'ORDER BY #__tb_services.srv_type, #__categories_1.ordering ASC,  #__tb_services.catid, #__tb_jrn_srv.ordering;';

		$db->setQuery( $query );
		$tb_services = $db->loadObjectList();
		return $tb_services;
	}

	/**
	 * Gets all additional Services linked to a choosen (or default) tour
	 * @return data to be used later
	 */ 
	 
	function getTBAddServices()
	{
		$db =& JFactory::getDBO();
		
		$id_tour = $this->_tid;

		$query = 	'SELECT #__tb_services.id, #__tb_services.srv_type, #__categories.title AS category, #__tb_services.checked, #__tb_services.pro_rata, #__tb_services.ordering, #__tb_services.published, #__tb_services.rate, #__categories_1.title AS type, #__tb_services.catid, #__tb_services.name '.
					'FROM #__categories AS #__categories_1 INNER JOIN ((#__tb_services INNER JOIN #__categories ON #__tb_services.srv_type = #__categories.id) INNER JOIN (#__tb_journey INNER JOIN #__tb_jrn_srv ON #__tb_journey.id = #__tb_jrn_srv.jrn_id) ON #__tb_services.id = #__tb_jrn_srv.srv_id) ON #__categories_1.id = #__tb_services.catid '.
					'WHERE ((#__tb_journey.id) = '.$id_tour.' AND (#__tb_journey.published) = 1 AND (#__categories.title) <> "inclusiv" ) '.
					'ORDER BY #__tb_services.srv_type, #__tb_jrn_srv.ordering;';

		$db->setQuery( $query );
		$tb_add_services = $db->loadObjectList();
		return $tb_add_services;
	}

	/**
	 * Gets all data linked hotels to a choosen (or default) tour
	 * @return data to be used later
	 */ 
	 
	function getTBHotels()
	{
		$db =& JFactory::getDBO();
		
		$id_tour = $this->_tid;

		$query = 	'SELECT #__tb_hotels.*, categories.title AS category, #__tb_jrn_htl.rate AS hotel_rate, #__tb_jrn_htl.id AS htl_id, #__tb_jrn_htl.accomodation '.
					'FROM #__categories AS categories INNER JOIN (#__tb_hotels INNER JOIN #__tb_jrn_htl ON #__tb_hotels.id = #__tb_jrn_htl.htl_id) ON categories.id = #__tb_hotels.catid '.
					'WHERE #__tb_jrn_htl.jrn_id = '.$id_tour.' '.
					'ORDER by #__tb_hotels.ordering, #__tb_jrn_htl.ordering;';
					
		$db->setQuery( $query );

		$tb_hotels = $db->loadObjectList();
		return $tb_hotels;

	}

/**
 * Gets all data linked feeders to a choosen (or default) tour
 * @return data to be used later
 */ 
	 
	function getTBFeeders()
	{
		$db =& JFactory::getDBO();

		$array = JRequest::getVar('season',  0, '', 'array');
		$this->setDId((int)$array[0]);
		
		$id_tour = $this->_tid;
		$id_date = $this->_did;

		$query = 	'SELECT * '.
					'FROM #__tb_feeder INNER JOIN #__tb_dat_prt ON #__tb_feeder.id = #__tb_dat_prt.prt_id '.
					'WHERE #__tb_dat_prt.dat_id = '.$id_date.' '.
					'ORDER by #__tb_feeder.ordering, #__tb_dat_prt.ordering;';
		$db->setQuery( $query );

		$tb_feeders = $db->loadObjectList();
		return $tb_feeders;

	}

/**
 * Sanitize Input
 * @return data to be used later
 */ 
	function cleanInput($input) {
	 
		$search = array(
			'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
			'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
			'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
			'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
		);
		 
			$output = preg_replace($search, '', $input);
			return $output;
	}

/**
 * Sanitize Input
 * @return data to be used later
 */ 
	function sanitize($input) {

		if (is_array($input)) {
			foreach($input as $var=>$val) {
				$output[$var] = $this->sanitize($val);
			}
		}
		else {
			if (get_magic_quotes_gpc()) {
				$input = stripslashes($input);
			}
			$input  = $this->cleanInput($input);
			$output = mysql_real_escape_string($input);
		}
		return $output;
	}

}
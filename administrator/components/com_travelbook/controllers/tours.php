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

/**
 * Travelbook Controller
 *
 * @package    com_travelbook
 * @subpackage administrator
 */
class TravelbooksControllerTours extends TravelbooksController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
		// Register Extra tasks

		$this->registerTask( 'show',  					'display' );
		$this->registerTask( 'detail', 					'detail' );
		$this->registerTask( 'apply',  					'apply' );
		$this->registerTask( 'save',				  	'save' );
		$this->registerTask( 'cancel',  				'cancel' );
		$this->registerTask( 'close', 				 	'close' );
		$this->registerTask( 'publish', 				'publish' );
		$this->registerTask( 'unpublish',				'unpublish' );
		$this->registerTask( 'orderup',					'orderup' );
		$this->registerTask( 'orderdown',				'orderdown' );
		$this->registerTask( 'saveorder',				'saveorder' );

		$this->registerTask( 'edit',					'edit' );
		$this->registerTask( 'delete',					'remove' );
		$this->registerTask( 'add',						'add' );

		$this->registerTask( 'addseason',				'addseason' );
		$this->registerTask( 'saveseason',				'saveseason' );
		$this->registerTask( 'canceladdseason',			'canceladdseason' );

		$this->registerTask( 'addservices',				'addservices' );
		$this->registerTask( 'saveservices',			'saveservices' );
		$this->registerTask( 'deleteservices',			'deleteservices' );
		$this->registerTask( 'cancelservices',			'canceladdseason' );
		$this->registerTask( 'publishTourService',		'publishTourService' );
		$this->registerTask( 'unpublishTourService',	'unpublishTourService' );
		$this->registerTask( 'publishTourService_inv',	'publishTourService_inv' );
		$this->registerTask( 'unpublishTourService_inv','unpublishTourService_inv' );

		$this->registerTask( 'addhotels',				'addhotels' );
		$this->registerTask( 'savehotels',				'savehotels' );
		$this->registerTask( 'deletehotels',			'deletehotels' );
		$this->registerTask( 'cancelhotels',			'canceladdseason' );
		$this->registerTask( 'publishTourHotel',		'publishTourHotel' );
		$this->registerTask( 'unpublishTourHotels',		'unpublishTourHotels' );
		$this->registerTask( 'publishTourHotel_inv',	'publishTourHotel_inv' );
		$this->registerTask( 'unpublishTourHotel_inv',	'unpublishTourHotel_inv' );

	}

	/***
	 * Standard display control structure
	***/
	function display()
	{
		global $mainframe;
		$stateVar = $mainframe->setUserState( $option_tours.'filter_order', 'tours.ordering' );
		$stateVar = $mainframe->setUserState( $option_tourss.'search', '' );

		JRequest::setVar( 'view', 'tours' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 0);
		parent::display();
	}

	/***
	 * Standard detail control structure
	***/
	function detail()
	{
		JRequest::setVar( 'view', 'tours' );
		JRequest::setVar( 'layout', 'detail'  );
		JRequest::setVar( 'hidemainmenu', 1);
		parent::display();
	}

	/***
	 * Standard add control structure
	***/
	function add()
	{
		JRequest::setVar( 'view', 'tours' );
		JRequest::setVar( 'layout', 'detail'  );
		JRequest::setVar( 'hidemainmenu', 0);
		parent::display();
	}

	/***
	 * Standard add control structure
	***/
	function addseason()
	{
		JRequest::setVar( 'view', 'tours' );
		JRequest::setVar( 'layout', 'addseason'  );
		JRequest::setVar( 'hidemainmenu', 1);
		parent::display();
	}

	/***
	 * Standard add control structure
	***/
	function addservices()
	{
		JRequest::setVar( 'view', 'tours_services' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 1);
		parent::display();
	}

	/***
	 * Standard add control structure
	***/
	function addhotels()
	{
		JRequest::setVar( 'view', 'tours_hotels' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 1);
		parent::display();
	}

	/***
	 * Standard edit control structure
	***/
	function edit()
	{
		$tids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$link = 'index.php?option=com_travelbook&task=tours.detail&tid[]='.$tids[0];
		$this->setRedirect($link);
	}

	/***
	 * save a record (and redirect to main page)
	 * @return void
	***/
	function apply()
	{
		$data = JRequest::get( 'post' );

		$model = $this->getModel('tours');

		if ($model->store($data)) {
			$msg = JText::_( 'Tour Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Tour' );
		}

		global $new_id;

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_travelbook&task=tours.detail&tid[]='.$new_id;
		
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$data = JRequest::get( 'post' );

		$model = $this->getModel('tours');

		if ($model->store($data)) {
			$msg = JText::_( 'Tour Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Tour' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_travelbook&task=tours.show&tid[]='.$data['id'];
		$this->setRedirect($link, $msg);
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function saveseason()
	{
		$data = JRequest::get( 'post' );

		$model = $this->getModel('seasons');

		if ($model->store($data)) {
			$msg = JText::_( 'Season Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Season' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$tid = JRequest::getVar('jrn_id',  '', '', 'string');
		$this->setRedirect( 'index.php?option=com_travelbook&task=tours.detail&tid[]='.$tid, $msg );

//		$link = 'index.php?option=com_travelbook&task=tours.show&tid[]='.$data['id'];
//		$this->setRedirect($link, $msg);
	}

	/**
	 * save records (and redirect to main page)
	 * @return void
	 */
	function saveservices()
	{

		if ( JRequest::getVar( 'cid' ) ) {
			$msg = JText::_( 'You cannot add this Service' );
		} else {
			if ( !(JRequest::getVar( 'cid_inv' )) ) {
				$msg = JText::_( 'Choose one or more Services you want to add to this Tour' );
			} else {
				$data = array();
				$data["jrn_id"] = JRequest::getVar( 'tid', 0, 'post', 'int' );
				$cids = JRequest::getVar( 'cid_inv', array(0), 'post', 'array' );
		
				if ($cids) {
					foreach ($cids as $cid) {
						$data["srv_id"] = $cid;
		
						$model = $this->getModel('tours_services');
				
						if ($model->store($data)) {
							$msg = JText::_( 'Service Added!' );
						} else {
							$msg = JText::_( 'Error Adding Service' );
						}
					}
				} 
			}
		}
		
		$tid = JRequest::getVar( 'tid', 0, 'post', 'int' );

		$link = 'index.php?option=com_travelbook&task=tours.addservices&tid[]='.$tid;
		$this->setRedirect($link, $msg);
	}

	/**
	 * save records (and redirect to main page)
	 * @return void
	 */
	function savehotels()
	{

		if ( JRequest::getVar( 'cid' ) ) {
			$msg = JText::_( 'You cannot add this Hotel' );
		} else {
			if ( !(JRequest::getVar( 'cid_inv' )) ) {
				$msg = JText::_( 'Choose one or more Hotels you want to add to this Tour' );
			} else {
				$data = array();
				$data["jrn_id"] = JRequest::getVar( 'tid', 0, 'post', 'int' );
				$cids = JRequest::getVar( 'cid_inv', array(0), 'post', 'array' );
				$id = JRequest::getVar( 'id', array(0), 'post', 'array' );
				$accomodations = JRequest::getVar( 'accomodation', array(0), 'post', 'array' );
				$rate = JRequest::getVar( 'rate', array(0), 'post', 'array' );

				if ($cids) {
					$i=0;
					foreach ($cids as $cid) {
						$data["htl_id"] = $id[$cid];
						$data["accomodation"] = $accomodations[$cid];	
						$data["rate"] = $rate[$cid];	
						$i++;
						$model = $this->getModel('tours_hotels');

						if ($model->store($data)) {
							$msg = JText::_( 'Hotel Added!' );
						} else {
							$msg = JText::_( 'Error Adding Hotel' );
						}
					}
				} 
			}
		}
		
		$tid = JRequest::getVar( 'tid', 0, 'post', 'int' );

		$link = 'index.php?option=com_travelbook&task=tours.addhotels&tid[]='.$tid;
		$this->setRedirect($link, $msg);
	}

	/**
	 * delete records
	 * @return void
	 */
	function deleteservices()
	{

		if ( JRequest::getVar( 'cid_inv' ) ) {
			$msg = JText::_( 'You cannot delete this Service!' );
		} else {
			if ( !(JRequest::getVar( 'cid' )) ) {
				$msg = JText::_( 'Choose one or more Services you want to delete from this Tour' );
			} else {
				$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
				if ($cids) {
					$model = $this->getModel('tours_services');
					if ($model->delete($data)) {
						$msg = JText::_( 'Service Deleted!' );
					} else {
						$msg = JText::_( 'Error Deleting Service' );
					}
				} 
			}
		}
		
		$tid = JRequest::getVar( 'tid', 0, 'post', 'int' );

		$link = 'index.php?option=com_travelbook&task=tours.addservices&tid[]='.$tid;
		$this->setRedirect($link, $msg);
	}

	/**
	 * delete records
	 * @return void
	 */
	function deletehotels()
	{

		if ( JRequest::getVar( 'cid_inv' ) ) {
			$msg = JText::_( 'You cannot delete this Hotel!' );
		} else {
			if ( !(JRequest::getVar( 'cid' )) ) {
				$msg = JText::_( 'Choose one or more Hotels you want to remove from this Tour' );
			} else {
				$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
				if ($cids) {
					$model = $this->getModel('tours_hotels');
					if ($model->delete($data)) {
						$msg = JText::_( 'Hotel Deleted!' );
					} else {
						$msg = JText::_( 'Error Deleting Hotel' );
					}
				} 
			}
		}
		
		$tid = JRequest::getVar( 'tid', 0, 'post', 'int' );

		$link = 'index.php?option=com_travelbook&task=tours.addhotels&tid[]='.$tid;
		$this->setRedirect($link, $msg);
	}

	/**
	 * closes editing a record
	 * @return void
	 */
	function close()
	{
		$this->setRedirect( 'index.php?option=com_travelbook&task=tours.show' );
	}

	/**
	 * remove record(s)
	 * @return void
	 */

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_travelbook&task=tours.show', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function canceladdseason()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_travelbook&task=tours.show', $msg );
	}

	/**
	 * remove record(s)
	 * @return void
	 */

	function remove()
	{
		$model = $this->getModel('tours');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Tours Could not be Deleted' );
		} else {
			$msg = JText::_( 'Tour(s) Deleted' );
		}

		$link = 'index.php?option=com_travelbook&task=tours.show';

		$this->setRedirect($link, $msg);
	}

	/**
	 * publish records
	 * @return void
	 */
	function publish()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$model = $this->getModel('tours');
		if(!$model->changeTours( $cids, 1 )) {
			$msg = JText::_( 'Error: One or More Tours Could not be Published' );
		} else {
			$msg = JText::_( 'Tour(s) Published' );
		}

		$this->setRedirect($link, $msg);
	}

	/**
	 * publish records
	 * @return void
	 */
	function unpublish()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$model = $this->getModel('tours');
		if(!$model->changeTours( $cids, 0 )) {
			$msg = JText::_( 'Error: One or More Tours Could not be Upublished' );
		} else {
			$msg = JText::_( 'Tour(s) Unpublished' );
		}

		$this->setRedirect($link, $msg);
	}

	/**
	 * order up records
	 * @return void
	 */
	function orderup()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$model = $this->getModel('tours');
		if(!$model->orderTours( $cids[0], -1 )) {
			$msg = JText::_( 'Error: The Tours Could not be Ordered up' );
		} else {
			$msg = JText::_( 'Tour(s) ordered up' );
		}

		$this->setRedirect($link, $msg);
	}

	/**
	 * order down records
	 * @return void
	 */
	function orderdown()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$model = $this->getModel('tours');
		if(!$model->orderTours( $cids[0], 1 )) {
			$msg = JText::_( 'Error: The Tours Could not be Ordered down' );
		} else {
			$msg = JText::_( 'Tour(s) ordered down' );
		}

		$this->setRedirect($link, $msg);
	}

	/**
	 * save new order
	 * @return void
	 */
	function saveorder()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$model = $this->getModel('tours');
		if(!$model->saveOrder( $cids )) {
			$msg = JText::_( 'Error: The new order Could not be saved' );
		} else {
			$msg = JText::_( 'New order saved' );
		}

		$this->setRedirect($link, $msg);
	}

	/**
	 * publish records
	 * @return void
	 */
	function publishTourService()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$tid = JRequest::getVar( 'tid', array(0), 'post', 'array' );

		$model = $this->getModel('services');
		if(!$model->changeServices( $cids, 1 )) {
			$msg = JText::_( 'Error: One or More Services Could not be Published' );
		} else {
			$msg = JText::_( 'Service(s) Published' );
		}
		
		$link = 'index.php?option=com_travelbook&task=tours.addservices&tid[]='.$tid;
		$this->setRedirect($link, $msg);
	}

	/**
	 * publish records
	 * @return void
	 */
	function unpublishTourService()
	{

		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$tid = JRequest::getVar( 'tid', array(0), 'post', 'array' );

		$model = $this->getModel('services');
		if(!$model->changeServices( $cids, 0 )) {
			$msg = JText::_( 'Error: One or More Services Could not be Upublished' );
		} else {
			$msg = JText::_( 'Service(s) Unpublished' );
		}

		$link = 'index.php?option=com_travelbook&task=tours.addservices&tid[]='.$tid;
		$this->setRedirect($link, $msg);
	}

	/**
	 * publish records
	 * @return void
	 */
	function publishTourService_inv()
	{
		$cids = JRequest::getVar( 'cid_inv', array(0), 'post', 'array' );
		$tid = JRequest::getVar( 'tid', array(0), 'post', 'array' );

		$model = $this->getModel('services');
		if(!$model->changeServices( $cids, 1 )) {
			$msg = JText::_( 'Error: One or More Services Could not be Published' );
		} else {
			$msg = JText::_( 'Service(s) Published' );
		}
		
		$link = 'index.php?option=com_travelbook&task=tours.addservices&tid[]='.$tid;
		$this->setRedirect($link, $msg);
	}

	/**
	 * publish records
	 * @return void
	 */
	function unpublishTourService_inv()
	{

		$cids = JRequest::getVar( 'cid_inv', array(0), 'post', 'array' );
		$tid = JRequest::getVar( 'tid', array(0), 'post', 'array' );

		$model = $this->getModel('services');
		if(!$model->changeServices( $cids, 0 )) {
			$msg = JText::_( 'Error: One or More Services Could not be Upublished' );
		} else {
			$msg = JText::_( 'Service(s) Unpublished' );
		}

		$link = 'index.php?option=com_travelbook&task=tours.addservices&tid[]='.$tid;
		$this->setRedirect($link, $msg);
	}

}
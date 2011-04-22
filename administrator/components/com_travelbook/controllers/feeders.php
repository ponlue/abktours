<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: feeders.php 2 2010-04-13 13:37:46Z WEB $
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
class TravelbooksControllerFeeders extends TravelbooksController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
		// Register Extra tasks

		$this->registerTask( 'show',  		'display' );
		$this->registerTask( 'detail', 		'detail' );
		$this->registerTask( 'apply',  		'apply' );
		$this->registerTask( 'save',	  	'save' );
		$this->registerTask( 'cancel',  	'cancel' );
		$this->registerTask( 'close', 	 	'close' );
		$this->registerTask( 'publish', 	'publish' );
		$this->registerTask( 'unpublish',	'unpublish' );
		$this->registerTask( 'orderup',		'orderup' );
		$this->registerTask( 'orderdown',	'orderdown' );
		$this->registerTask( 'saveorder',	'saveorder' );

		$this->registerTask( 'edit',		'edit' );
		$this->registerTask( 'delete',		'remove' );
		$this->registerTask( 'add',			'add' );
	}

	/***
	 * Standard display control structure
	***/
	function display()
	{
		global $mainframe;
		$stateVar = $mainframe->setUserState( $option_feeders.'filter_order', 'feeders.ordering' );
		$stateVar = $mainframe->setUserState( $option_feeders.'search', '' );

		JRequest::setVar( 'view', 'feeders' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 0);
		parent::display();
	}

	/***
	 * Standard detail control structure
	***/
	function detail()
	{
		JRequest::setVar( 'view', 'feeders' );
		JRequest::setVar( 'layout', 'detail'  );
		JRequest::setVar( 'hidemainmenu', 1);
		parent::display();
	}

	/***
	 * Standard add control structure
	***/
	function add()
	{
		JRequest::setVar( 'view', 'feeders' );
		JRequest::setVar( 'layout', 'detail'  );
		JRequest::setVar( 'hidemainmenu', 0);
		parent::display();
	}

	/***
	 * Standard edit control structure
	***/
	function edit()
	{
		$tids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$link = 'index.php?option=com_travelbook&task=feeders.detail&tid[]='.$tids[0];
		$this->setRedirect($link);
	}

	/***
	 * save a record (and redirect to main page)
	 * @return void
	***/
	function apply()
	{
		$data = JRequest::get( 'post' );

		$model = $this->getModel('feeders');

		if ($model->store($data)) {
			$msg = JText::_( 'Feeder Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Feeder' );
		}

		global $new_id;

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_travelbook&task=feeders.detail&tid[]='.$new_id;
		
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$data = JRequest::get( 'post' );

		$model = $this->getModel('feeders');

		if ($model->store($data)) {
			$msg = JText::_( 'Feeder Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Feeder' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_travelbook&task=feeders.show&tid[]='.$data['id'];
		$this->setRedirect($link, $msg);
	}

	/**
	 * closes editing a record
	 * @return void
	 */
	function close()
	{
		$this->setRedirect( 'index.php?option=com_travelbook&task=feeders.show' );
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
		$this->setRedirect( 'index.php?option=com_travelbook&task=feeders.show', $msg );
	}

	/**
	 * remove record(s)
	 * @return void
	 */

	function remove()
	{
		$model = $this->getModel('feeders');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Feeders Could not be Deleted' );
		} else {
			$msg = JText::_( 'Feeder(s) Deleted' );
		}

		$link = 'index.php?option=com_travelbook&task=feeders.show';

		$this->setRedirect($link, $msg);
	}

	/**
	 * publish records
	 * @return void
	 */
	function publish()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$model = $this->getModel('feeders');
		if(!$model->changeFeeders( $cids, 1 )) {
			$msg = JText::_( 'Error: One or More Feeders Could not be Published' );
		} else {
			$msg = JText::_( 'Feeder(s) Published' );
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

		$model = $this->getModel('feeders');
		if(!$model->changeFeeders( $cids, 0 )) {
			$msg = JText::_( 'Error: One or More Feeders Could not be Upublished' );
		} else {
			$msg = JText::_( 'Feeder(s) Unpublished' );
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

		$model = $this->getModel('feeders');
		if(!$model->orderFeeders( $cids[0], -1 )) {
			$msg = JText::_( 'Error: The Feeders Could not be Ordered up' );
		} else {
			$msg = JText::_( 'Feeder(s) ordered up' );
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

		$model = $this->getModel('feeders');
		if(!$model->orderFeeders( $cids[0], 1 )) {
			$msg = JText::_( 'Error: The Feeders Could not be Ordered down' );
		} else {
			$msg = JText::_( 'Feeder(s) ordered down' );
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

		$model = $this->getModel('feeders');
		if(!$model->saveOrder( $cids )) {
			$msg = JText::_( 'Error: The new order Could not be saved' );
		} else {
			$msg = JText::_( 'New order saved' );
		}

		$this->setRedirect($link, $msg);
	}


}
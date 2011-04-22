<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: seasons.php 2 2010-04-13 13:37:46Z WEB $
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
class TravelbooksControllerSeasons extends TravelbooksController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

// Register Extra tasks
		$this->registerTask( 'show',  						'display' );
		$this->registerTask( 'detail', 						'detail' );
		$this->registerTask( 'apply',  						'apply' );
		$this->registerTask( 'save',	  					'save' );
		$this->registerTask( 'cancel',  					'cancel' );
		$this->registerTask( 'close', 	 					'close' );
		$this->registerTask( 'publish', 					'publish' );
		$this->registerTask( 'unpublish',					'unpublish' );
		$this->registerTask( 'orderup',						'orderup' );
		$this->registerTask( 'orderdown',					'orderdown' );
		$this->registerTask( 'saveorder',					'saveorder' );

		$this->registerTask( 'edit',						'edit' );
		$this->registerTask( 'delete',						'remove' );
		$this->registerTask( 'add',							'add' );
		
		$this->registerTask( 'addfeeders',					'addfeeders' );

		$this->registerTask( 'publishSeasonFeeder',			'publishSeasonFeeder' );
		$this->registerTask( 'unpublishSeasonFeeder',		'unpublishSeasonFeeder' );
		$this->registerTask( 'publishSeasonFeeder_inv',		'publishSeasonFeeder_inv' );
		$this->registerTask( 'unpublishSeasonFeeder_inv',	'unpublishSeasonFeeder_inv' );
		$this->registerTask( 'savefeeders',					'savefeeders' );
		$this->registerTask( 'deletefeeders',				'deletefeeders' );
		$this->registerTask( 'cancelfeeders',				'cancel' );	

	}

	/***
	 * Standard display control structure
	***/
	function display()
	{
		global $mainframe;
		$stateVar = $mainframe->setUserState( $option_seasons.'filter_order', 'seasons.ordering' );
		$stateVar = $mainframe->setUserState( $option_seasons.'search', '' );

		JRequest::setVar( 'view', 'seasons' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 0);
		parent::display();
	}

	/***
	 * Standard detail control structure
	***/
	function detail()
	{
		JRequest::setVar( 'view', 'seasons' );
		JRequest::setVar( 'layout', 'detail'  );
		JRequest::setVar( 'hidemainmenu', 1);
		parent::display();
	}

	/***
	 * Standard add control structure
	***/
	function add()
	{
		JRequest::setVar( 'view', 'seasons' );
		JRequest::setVar( 'layout', 'detail'  );
		JRequest::setVar( 'hidemainmenu', 0);
		parent::display();
	}

	/***
	 * Standard add control structure
	***/
	function addfeeders()
	{
		JRequest::setVar( 'view', 'seasons_feeders' );
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
		$link = 'index.php?option=com_travelbook&task=seasons.detail&tid[]='.$tids[0];
		$this->setRedirect($link);
	}

	/***
	 * save a record (and redirect to main page)
	 * @return void
	***/
	function apply()
	{
		$data = JRequest::get( 'post' );

		$model = $this->getModel('seasons');

		if ($model->store($data)) {
			$msg = JText::_( 'Season Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Season' );
		}

		global $new_id;

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_travelbook&task=seasons.detail&tid[]='.$new_id;
		
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$data = JRequest::get( 'post' );

		$model = $this->getModel('seasons');

		if ($model->store($data)) {
			$msg = JText::_( 'Season Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Season' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_travelbook&task=seasons.show&tid[]='.$data['id'];
		$this->setRedirect($link, $msg);
	}

	/**
	 * closes editing a record
	 * @return void
	 */
	function close()
	{
		$this->setRedirect( 'index.php?option=com_travelbook&task=seasons.show' );
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
		$this->setRedirect( 'index.php?option=com_travelbook&task=seasons.show', $msg );
	}

	/**
	 * remove record(s)
	 * @return void
	 */

	function remove()
	{
		$model = $this->getModel('seasons');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Seasons Could not be Deleted' );
		} else {
			$msg = JText::_( 'Season(s) Deleted' );
		}

		$link = 'index.php?option=com_travelbook&task=seasons.show';

		$this->setRedirect($link, $msg);
	}

	/**
	 * publish records
	 * @return void
	 */
	function publish()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$model = $this->getModel('seasons');
		if(!$model->changeSeasons( $cids, 1 )) {
			$msg = JText::_( 'Error: One or More Seasons Could not be Published' );
		} else {
			$msg = JText::_( 'Season(s) Published' );
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

		$model = $this->getModel('seasons');
		if(!$model->changeSeasons( $cids, 0 )) {
			$msg = JText::_( 'Error: One or More Seasons Could not be Upublished' );
		} else {
			$msg = JText::_( 'Season(s) Unpublished' );
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

		$model = $this->getModel('seasons');
		if(!$model->orderSeasons( $cids[0], -1 )) {
			$msg = JText::_( 'Error: The Seasons Could not be Ordered up' );
		} else {
			$msg = JText::_( 'Season(s) ordered up' );
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

		$model = $this->getModel('seasons');
		if(!$model->orderSeasons( $cids[0], 1 )) {
			$msg = JText::_( 'Error: The Seasons Could not be Ordered down' );
		} else {
			$msg = JText::_( 'Season(s) ordered down' );
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

		$model = $this->getModel('seasons');
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
	function publishSeasonFeeder()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$tid = JRequest::getVar( 'tid', array(0), 'post', 'array' );

		$model = $this->getModel('feeders');
		if(!$model->changeFeeders( $cids, 1 )) {
			$msg = JText::_( 'Error: One or More Feeders Could not be Published' );
		} else {
			$msg = JText::_( 'Feeder(s) Published' );
		}
		
		$link = 'index.php?option=com_travelbook&task=seasons.addfeeders&tid[]='.$tid;
		$this->setRedirect($link, $msg);
	}

	/**
	 * publish records
	 * @return void
	 */
	function unpublishSeasonFeeder()
	{

		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$tid = JRequest::getVar( 'tid', array(0), 'post', 'array' );

		$model = $this->getModel('feeders');
		if(!$model->changeFeeders( $cids, 0 )) {
			$msg = JText::_( 'Error: One or More Feeders Could not be Upublished' );
		} else {
			$msg = JText::_( 'Feeder(s) Unpublished' );
		}

		$link = 'index.php?option=com_travelbook&task=seasons.addfeeders&tid[]='.$tid;
		$this->setRedirect($link, $msg);
	}

	/**
	 * publish records
	 * @return void
	 */
	function publishSeasonFeeder_inv()
	{
		$cids = JRequest::getVar( 'cid_inv', array(0), 'post', 'array' );
		$tid = JRequest::getVar( 'tid', array(0), 'post', 'array' );

		$model = $this->getModel('feeders');
		if(!$model->changeFeeders( $cids, 1, 'seasons.addfeeders&tid[]='.$tid[0] )) {
			$msg = JText::_( 'Error: One or More Feeders Could not be Published' );
		} else {
			$msg = JText::_( 'Feeder(s) Published' );
		}
	}

	/**
	 * publish records
	 * @return void
	 */
	function unpublishSeasonFeeder_inv()
	{
		$cids = JRequest::getVar( 'cid_inv', array(0), 'post', 'array' );
		$tid = JRequest::getVar( 'tid', array(0), 'post', 'array' );

		$model = $this->getModel('feeders');
		if(!$model->changeFeeders( $cids, 0, 'seasons.addfeeders&tid[]='.$tid[0] )) {
			$msg = JText::_( 'Error: One or More Feeders Could not be Upublished' );
		} else {
			$msg = JText::_( 'Feeder(s) Unpublished' );
		}
	}

	/**
	 * save records (and redirect to main page)
	 * @return void
	 */
	function savefeeders()
	{
		if ( JRequest::getVar( 'cid' ) ) {
			$msg = JText::_( 'You cannot add this Feeder' );
		} else {
			if ( !(JRequest::getVar( 'cid_inv' )) ) {
				$msg = JText::_( 'Choose one or more Feeders you want to add to this Season' );
			} else {
				$data = array();
				$data["dat_id"] = JRequest::getVar( 'tid', 0, 'post', 'int' );
				$cids = JRequest::getVar( 'cid_inv', array(0), 'post', 'array' );
				$markups = JRequest::getVar( 'markup', array(0), 'post', 'array' );
				$id = JRequest::getVar( 'id', array(0), 'post', 'array' );

				if ($cids) {
					$i=0;
					foreach ($cids as $cid) {
						$data["prt_id"] = $id[$cid];
						$data["markup"] = $markups[$cid];	
						$i++;
						$model = $this->getModel('seasons_feeders');
				
						if ($model->store($data)) {
							$msg = JText::_( 'Feeder Added!' );
						} else {
							$msg = JText::_( 'Error Adding Feeder' );
						}
					}
				} 
			}
		}
		
		$tid = JRequest::getVar( 'tid', 0, 'post', 'int' );

		$link = 'index.php?option=com_travelbook&task=seasons.addfeeders&tid[]='.$tid;
		$this->setRedirect($link, $msg);
	}

	/**
	 * delete records
	 * @return void
	 */
	function deletefeeders()
	{

		if ( JRequest::getVar( 'cid_inv' ) ) {
			$msg = JText::_( 'You cannot delete this Feeder!' );
		} else {
			if ( !(JRequest::getVar( 'cid' )) ) {
				$msg = JText::_( 'Choose one or more Feeders you want to remove from this Season' );
			} else {
				$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
				if ($cids) {
					$model = $this->getModel('seasons_feeders');
					if ($model->delete($data)) {
						$msg = JText::_( 'Feeder(s) Deleted!' );
					} else {
						$msg = JText::_( 'Error Deleting Feeder(s)' );
					}
				} 
			}
		}
		
		$tid = JRequest::getVar( 'tid', 0, 'post', 'int' );

		$link = 'index.php?option=com_travelbook&task=seasons.addfeeders&tid[]='.$tid;
		$this->setRedirect($link, $msg);
	}

}
<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: travelbooks.php 2 2010-04-13 13:37:46Z WEB $
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
class TravelbooksControllerTravelbooks extends TravelbooksController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
		// Register Extra tasks

		$this->registerTask( 'show',  	'display' );
		$this->registerTask( 'remove',  'remove' );
		$this->registerTask( 'apply',  	'apply' );
		$this->registerTask( 'save',  	'save' );
		$this->registerTask( 'cancel',  'cancel' );
		$this->registerTask( 'close',  	'cancel' );

		$this->registerTask( 'send',  	'send' );
	}

	/**
	 * Standard display control structure
	 * 
	 */
	function display()
	{
//		$this->view = & $this->getView('config', 'html', '');
//		$this->view->_layout = 'Kopievonconfig';

		JRequest::setVar( 'view', 'travelbooks' );
		JRequest::setVar( 'layout', 'default' );
		JRequest::setVar('hidemainmenu', 0);

		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function apply()
	{
		$data = JRequest::get( 'post' );

		$model = $this->getModel('follower');

		if ($model->store($data)) {
			$msg = JText::_( 'Follower Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Follower' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_travelbook&task=follower.show&fid[]='.$data['id'].'&uid[]='.$data['userid'];
		
//	$link = 'index.php?option=com_travelbook&task=follower.edit';

		$this->setRedirect($link, $msg);
	}
	
	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$data = JRequest::get( 'post' );

		$model = $this->getModel('follower');

		if ($model->store($data)) {
			$msg = JText::_( 'Follower Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Follower' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_travelbook&task=followers.show';
		$this->setRedirect($link, $msg);
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_travelbook&task=followers.show', $msg );
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$tids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$data = JRequest::get( 'post' );

		if ( count( $tids ) >= $data->count ) {
			$link = 'index.php?option=com_travelbook&task=followers.show';
		} else {
			$link = 'index.php?option=com_travelbook&task=follower.show&uid[]='.$data['userid'];
		}

		$model = $this->getModel('follower');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Followers Could not be Deleted' );
		} else {
			$msg = JText::_( 'Followers(s) Deleted' );
		}

		$this->setRedirect($link, $msg);
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function send()
	{
$msg= 'TEST';
		$link = 'http://www.google.de';
		$this->setRedirect($link, $msg);
	}

}
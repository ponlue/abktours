<?php
/**
 * @version		$Id: session.php 14401 2010-01-26 14:10:00Z louis $
 * @package		Joomla.Legacy
 * @subpackage	1.5
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

// Register legacy classes for autoloading
JLoader::register('JTableSession', JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table'.DS.'session.php');

/**
 * Legacy class, use {@link JTableSession} instead
 *
 * @deprecated	As of version 1.5
 * @package	Joomla.Legacy
 * @subpackage	1.5
 */
class mosSession extends JTableSession
{
	/**
	 * Constructor
	 */
	function __construct(&$db)
	{
		parent::__construct(  $db );
	}

	function mosSession(&$db)
	{
		parent::__construct( $db );
	}

	/**
	 * Encodes a session id
	 */
	function hash( $value )
	{
		global $mainframe;

		if (phpversion() <= '4.2.1') {
			$agent = getenv( 'HTTP_USER_AGENT' );
		} else {
			$agent = $_SERVER['HTTP_USER_AGENT'];
		}

		return md5( $agent . $mainframe->getCfg('secret') . $value . $_SERVER['REMOTE_ADDR'] );
	}

	/**
	 * Set the information to allow a session to persist
	 */
	function persist()
	{
		global $mainframe;

		$usercookie = mosGetParam( $_COOKIE, 'usercookie', null );
		if ($usercookie) {
			// Remember me cookie exists. Login with usercookie info.
			$mainframe->login( $usercookie['username'], $usercookie['password'] );
		}
	}

	/**
	 * Legacy Method, use {@link JTable::reorder()} instead
	 * @deprecated As of 1.5
	 */
	function updateOrder( $where='' )
	{
		return $this->reorder( $where );
	}

	/**
	 * Legacy Method, use {@link JTable::publish()} instead
	 * @deprecated As of 1.0.3
	 */
	function publish_array( $cid=null, $publish=1, $user_id=0 )
	{
		$this->publish( $cid, $publish, $user_id );
	}

	/**
	 * Legacy Method, use {@link JTable::publish()} instead
	 * @deprecated As of 1.5
	 */
	function setFromRequest( $key, $varName, $default=null )
	{
		if (isset( $_REQUEST[$varName] )) {
			return $_SESSION[$key] = $_REQUEST[$varName];
		} else if (isset( $_SESSION[$key] )) {
			return $_SESSION[$key];
		} else {
			return $_SESSION[$key] = $default;
		}
	}
}
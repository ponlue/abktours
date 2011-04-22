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

jimport('joomla.filesystem.path');

/*** required standard extention for version ***/
JLoader::register('TravelbookVersion', JPATH_ADMINISTRATOR .DS.'components'.DS.'com_travelbook'.DS.'version.php' );

/*** Require the base controller ***/
require_once( JPATH_COMPONENT.DS.'controller.php' );

$cmd = JRequest::getCmd('task', 'travelbooks.show');

if (strpos($cmd, '.') != false) {
	// We have a defined controller/task pair -- lets split them
	list($controllerName, $task) = explode('.', $cmd);
	
	// Define the controller name and path
	$controllerName	= strtolower($controllerName);
	$controllerPath	= JPATH_COMPONENT.DS.'controllers'.DS.$controllerName.'.php';
	
	// If the controller file path exists, include it ... else lets die with a 500 error
	if (file_exists($controllerPath)) {
		require_once($controllerPath);
	} else {
		JError::raiseError(500, 'Invalid Controller');
	}
} else {
	// Base controller, just set the task 
	$controllerName = null;
	$task = $cmd;
}
	
// Set the name for the controller and instantiate it
$controllerClass = 'TravelbooksController'.ucfirst($controllerName);
if (class_exists($controllerClass)) {
	$controller = new $controllerClass();
} else {
	JError::raiseError(500, 'Invalid Controller Class - '.$controllerClass );
}

// Perform the Request task
$controller->execute($task);
// Redirect if set by the controller
$controller->redirect();
		
$version = new TravelbookVersion();

echo		"<div class='smallgrey'>TRAVELbook Version ".$version->getVersion().", ".$version->getCopyright(). 
				"<a href='http://www.demo-page.de' target='_blank' class='smallgrey'>".
					"<img src='http://www.demo-page.de/templates/demo-page/favicon.ico' height='10' width='10' alt='Suchmaschinenoptimierung und Marketing mit Demo Page'> ".
					"Demo Page".
				"</a>, all rights reserved.".
			"</div>";
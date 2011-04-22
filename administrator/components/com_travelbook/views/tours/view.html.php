<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: view.html.php 2 2010-04-13 13:37:46Z WEB $
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

jimport('joomla.html.pane');
jimport( 'joomla.application.component.view');

/**
 * Travelbook View
 *
 * @package    Travelbook
 * @subpackage administrator
 */ 
class TravelbooksViewTours extends JView
{
	/** 
	 * display method of Travelbook view
	 * @return void
	 **/
	function display($tpl = null)
	{

		$db =& JFactory::getDBO();
		
		$document = &JFactory::getDocument();
		$document->addStyleSheet( 'components/com_travelbook/assets/css/travelbook.css' );
		$document->addStyleSheet( 'components/com_travelbook/assets/css/calendar.css' );
		$document->addScript( 'components/com_travelbook/assets/js/calendar.js' );


		$tour	=& $this->get('tour');
		$isNew        = ($tour->id < 1);
		
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Hello' ).': <small><small>[ ' . $text.' ]</small></small>' );


// Title and Ikons
		$history = Jrequest::getVar('history','tours.cancel');
		$task = JRequest::getCmd('task', 'travelbooks.show');
		switch ($task) {
			case 'tours.show':
					JToolBarHelper::title(   JText::_( 'TB Tours' ).' <small><small>[ ' .JText::_( 'TB List' ).' ]</small></small>' , 'tour');
					JToolBarHelper::publish( 'tours.publish', 'Publish' );
					JToolBarHelper::unpublish( 'tours.unpublish', 'Unpublish' );
					JToolBarHelper::deleteListX( JText::_( 'TB Are You sure?' ),'tours.delete', 'Delete' );
					JToolBarHelper::editListX( 'tours.edit', 'Edit' );
					JToolBarHelper::addNewX( 'tours.add', 'New' );
				break;
			case 'tours.detail':
					JToolBarHelper::addNew( 'tours.addho
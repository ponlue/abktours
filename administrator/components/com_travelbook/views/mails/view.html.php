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
class TravelbooksViewMails extends JView
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

		$mail	=& $this->get('mail');
		$isNew	= ($mail->id < 1);

// Title and Ikons
		$history = Jrequest::getVar('history','mails.cancel');
		$task = JRequest::getCmd('task', 'travelbooks.show');
		switch ($task) {
			case 'mails.show':
					JToolBarHelper::title(   JText::_( 'TB Mail' ).' <small><small>[ ' .JText::_( 'TB List' ).' ]</small></small>' , 'mail');
					JToolBarHelper::publish( 'mails.publish', 'Publish' );
					JToolBarHelper::unpublish( 'mails.unpublish', 'Unpublish' );
					JToolBarHelper::deleteListX( JText::_( 'TB Are You sure?' ),'mails.delete', 'Delete' );
					JToolBarHelper::editListX( 'mails.edit', 'Edit' );
					JToolBarHelper::addNewX( 'mails.add', 'New' );
				break;
			case 'mails.detail':
					JToolBarHelper::title(   JText::_( 'TB Mail' ).' <small><small>[ ' .JText::_( 'TB Details' ).' ]</small></small>' , 'mail');
					JToolBarHelper::save( 'mails.save', 'Save' );
					JToolBarHelper::apply( 'mails.apply', 'Apply' );
					JToolBarHelper::cancel( $history, 'Cancel' );
				break;
			case 'mails.add':
					JToolBarHelper::title(   JText::_( 'TB Mail' ).' <small><small>[ ' .JText::_( 'TB Add new Mail' ).' ]</small></small>' , 'mail');
					JToolBarHelper::save( 'mails.save', 'Save' );
					JToolBarHelper::apply( 'mails.apply', 'Apply' );
					JToolBarHelper::cancel( 'mails.cancel', 'Cancel' );
				break;
		}

// Filters and co
		global $mainframe, $option_mail, $where, $pageNav;
	
		$filter_order		= $mainframe->getUserStateFromRequest( $option_mail.'filter_order', 		'filter_order', 	'mails.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option_mail.'filter_order_Dir',		'filter_order_Dir',	'',					'word' );
		$filter_state 		= $mainframe->getUserStateFromRequest( $option_mail.'filter_state', 		'filter_state', 	'',					'word' );
		$search 			= $mainframe->getUserStateFromRequest( $option_mail.'search', 				'search', 			'',					'string' );
		$search 			= JString::strtolower( $search );
	
		$limit				= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart			= $mainframe->getUserStateFromRequest($option_mail.'.limitstart', 'limitstart', 0, 'int');

		$where = array();
	
		if ( $search ) {
			$where[] = 'mails.subject LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'mails.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'mails.published = 0';
			}
		}
	
		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

// get the total number of records
		$total	=& $this->get('total');
		$this->assignRef('total', $total);	

		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );

// get the subset (based on limits) of required records
		$mails	=& $this->get('subset');
		$this->assignRef('mails', $mails);	

// get the mails
		$mail	=& $this->get('mail');
		$this->assignRef('mail', $mail);	

// state filter
		$lists['state']	= JHTML::_('grid.state',  $filter_state );
// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
// search filter
		$lists['search']= $search;
		$this->assignRef('lists',		$lists);	

// Details
// build the html radio buttons for published
	$details['published'] = JHTML::_('select.booleanlist',  'published', '', $mail->published );
	$this->assignRef('details',		$details);	

	parent::display($tpl);
		
	}

/**
* render Support
*/
	 function renderSupport () {
	 	$output = '';

		$output = '<div style="padding: 5px;">';
		$output .= "<table class='adminlist'>\n";

		$output .= "	<thead>\n";
		$output .= "		<tr class='sortable'>\n";
		$output .= "			<th  width='50%' nowrap='nowrap' style='text-align: left;'> ".JText::_( 'TB String' )."</th>\n";
		$output .= "			<th  width='50%' nowrap='nowrap' style='text-align: left;'> ".JText::_( 'TB Replace' )."</th>\n";
		$output .= "		</tr>\n";
		$output .= "	</thead>\n";

		$output .= "	<tr class='row0'>\n";
		$output .= "		<td>\n";
		$output .= "##clients.title##";
		$output .=  "		</td>\n";
		$output .= "		<td style='text-align: left;'>\n";
		$output .= JText::_( 'TB Title' );
		$output .=  "		</td>\n";
		$output .=  "	</tr>\n";

		$output .= "	<tr class='row0'>\n";
		$output .= "		<td>\n";
		$output .= "##clients.first_name##";
		$output .=  "		</td>\n";
		$output .= "		<td style='text-align: left;'>\n";
		$output .= JText::_( 'TB FIRST NAME' );
		$output .=  "		</td>\n";
		$output .=  "	</tr>\n";

		$output .= "	<tr class='row0'>\n";
		$output .= "		<td>\n";
		$output .= "##clients.last_name##";
		$output .=  "		</td>\n";
		$output .= "		<td style='text-align: left;'>\n";
		$output .= JText::_( 'TB LAST NAME' );
		$output .=  "		</td>\n";
		$output .=  "	</tr>\n";

		$output .= "	<tr class='row0'>\n";
		$output .= "		<td>\n";
		$output .= "##clients.birthdate##";
		$output .=  "		</td>\n";
		$output .= "		<td style='text-align: left;'>\n";
		$output .= JText::_( 'TB BIRTHDATE' );
		$output .=  "		</td>\n";
		$output .=  "	</tr>\n";

		$output .= "	<tr class='row0'>\n";
		$output .= "		<td>\n";
		$output .= "##clients.street##";
		$output .=  "		</td>\n";
		$output .= "		<td style='text-align: left;'>\n";
		$output .= JText::_( 'TB STREET' );
		$output .=  "		</td>\n";
		$output .=  "	</tr>\n";

		$output .= "	<tr class='row0'>\n";
		$output .= "		<td>\n";
		$output .= "##clients.cip##";
		$output .=  "		</td>\n";
		$output .= "		<td style='text-align: left;'>\n";
		$output .= JText::_( 'TB PLZ' );
		$output .=  "		</td>\n";
		$output .=  "	</tr>\n";

		$output .= "	<tr class='row0'>\n";
		$output .= "		<td>\n";
		$output .= "##clients.city##";
		$output .=  "		</td>\n";
		$output .= "		<td style='text-align: left;'>\n";
		$output .= JText::_( 'TB CITY' );
		$output .=  "		</td>\n";
		$output .=  "	</tr>\n";

		$output .= "	<tr class='row0'>\n";
		$output .= "		<td>\n";
		$output .= "##clients.country##";
		$output .=  "		</td>\n";
		$output .= "		<td style='text-align: left;'>\n";
		$output .= JText::_( 'TB COUNTRY' );
		$output .=  "		</td>\n";
		$output .=  "	</tr>\n";

		$output .= "	<tr class='row0'>\n";
		$output .= "		<td>\n";
		$output .= "##clients.telefone##";
		$output .=  "		</td>\n";
		$output .= "		<td style='text-align: left;'>\n";
		$output .= JText::_( 'TB FON' );
		$output .=  "		</td>\n";
		$output .=  "	</tr>\n";

		$output .= "	<tr class='row0'>\n";
		$output .= "		<td>\n";
		$output .= "##clients.email##";
		$output .=  "		</td>\n";
		$output .= "		<td style='text-align: left;'>\n";
		$output .= JText::_( 'TB MAIL' );
		$output .=  "		</td>\n";
		$output .=  "	</tr>\n";

		$output .= "	<tr class='row0'>\n";
		$output .= "		<td>\n";
		$output .= "##clients.remark##";
		$output .=  "		</td>\n";
		$output .= "		<td style='text-align: left;'>\n";
		$output .= JText::_( 'TB REMARK' );
		$output .=  "		</td>\n";
		$output .=  "	</tr>\n";

		$output .= "</table>\n";
		$output .= '</div>';
		
		return $output;
	 }
	
}
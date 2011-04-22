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
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Travelbook Controller
 *
 * @package		Joomla
 * @subpackage	Travelbooks
 * @since 1.5
 */
class TravelbookControllerTravelbook extends TravelbookController
{

/**
* Method to call the edit form
*/
	function apply()
	{
		$this->save();

		$this->sendmail();
		$message = JText::_('OKAY');

		$this->ende();
	}

/**
* Method to call the edit form
*/
	function ende()
	{
		$post = JRequest::getVar('redirect', '', 'post', 'string');

		$menu = &JSite::getMenu();

		if ( is_null( $menu->getItem($post)->link ) ) {
			$this->setRedirect(JURI::base());
		} else {
			$this->setRedirect($menu->getItem($post)->link);
		}
	}


/**
* Saves the record on an edit form submit
*/
	function save()
	{

// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

// Get some objects from the JApplication
		$db		=& JFactory::getDBO();

// get data for client
		$post = JRequest::getVar('tbclient', array(), 'post', 'array');
		$model = $this->getModel('travelbook');

		$post = $model->sanitize($post);

		$model = $this->getModel('client');

		$post['birthdate'] = & JFactory::getDate( $post['birthdate'], 0 )->toMySQL();

// get User IP addresse
		$VisitorIp = NULL;
		TravelbookHelperTravelbook::getVisitorIp( $VisitorIp );
		$post['ip']=$VisitorIp;

		if ($model->store($post)) {
			$msg = JText::_( 'TB Client Saved' );
		} else {
			$msg = JText::_( 'TB Error Saving Client' );
		}

// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();

		global $new_id_clt;

// get data for guests
		$post = JRequest::getVar('tbguests', array(), 'post', 'array');
		if ($post) {
			$model = $this->getModel('travelbook');
			$post = $model->sanitize($post);	
			$model = $this->getModel('guests');
			foreach ($post as $guest) {
				$guest['birthdate'] = & JFactory::getDate( $guest['birthdate'], 0 )->toMySQL();
				if ($model->store($guest,$new_id_clt)) {
					$msg = JText::_( 'TB Guests Saved' );
				} else {
					$msg = JText::_( 'TB Error Saving Guests' );
				}
			}
// Check the table in so it can be edited.... we are done with it anyway
//			$model->checkin();
		}

// get data for guests
		$post = JRequest::getVar('tbtour', array(), 'post', 'array');
		$post['clt_id']=$new_id_clt;

		$model = $this->getModel('client_season');

		if ($model->store($post)) {
			$msg = JText::_( 'TB GuestSeason Saved' );
		} else {
			$msg = JText::_( 'TB Error Saving GuestSeason' );
		}

	}

/**
* Sends a mail to client
*
* @acces public
* @since 1.5
*/
	function sendmail()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

// get data for client
		$client = JRequest::getVar('tbclient', array(), 'post', 'array');
		$client['birthdate'] = & JFactory::getDate( $client['birthdate'], 0 )->toMySQL();
// get data for guests
		$guests = JRequest::getVar('tbguests', array(), 'post', 'array');
		if ($guests) {
			foreach ($guests as $guest) {
				$guest['birthdate'] = & JFactory::getDate( $guest['birthdate'], 0 )->toMySQL();
			}
		}

// get data for tour
		$tour = JRequest::getVar('tbtour', array(), 'post', 'array');
		$tour['clt_id']=$new_id_clt;

// get User IP addresse
		$VisitorIp = NULL;
		TravelbookHelperTravelbook::getVisitorIp( $VisitorIp );
		$post['ip']=$VisitorIp;

// get email body
		$model = $this->getModel('mail');
		$mail_data = $model->getmail();

		foreach ($mail_data as $email) {
// Send mail
			$from=$email->from;
			$fromname=$email->fromname;
			$recipient=$client['email'];
			$subject=$email->subject;
			$body=$email->mailbody;
			$body=$this->replacemarker($email->mailbody, $client, $guests);
			$cc = $email->cc;
			$bcc = $email->bcc;
			if (!(is_null($from))) {

				JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode=1, $cc=null, $bcc=null, $attachment=null, $replyto=null, $replytoname=null);
			}	
		}
	}

/**
* Replaces marker in mailbody
*
* @acces public
* @since 1.5
*/
	function replacemarker($body, $kunde, $gast)
	{

	$suche = array();
	$suche[] = "##clients.title##";
	$suche[] = "##clients.first_name##";
	$suche[] = "##clients.last_name##";
	$suche[] = "##clients.birthdate##";
	$suche[] = "##clients.street##";
	$suche[] = "##clients.cip##";
	$suche[] = "##clients.city##";
	$suche[] = "##clients.country##";
	
	$suche[] = "##clients.telefone##";
	$suche[] = "##clients.email##";
	$suche[] = "##clients.remark##";

	$body_clean = str_replace($suche, $kunde, $body);
	$body_clean = str_replace('##today##', JHTML::_('date', date(), JText::_('DATE_FORMAT_LC1')), $body_clean);
	return $body_clean;
	}

}
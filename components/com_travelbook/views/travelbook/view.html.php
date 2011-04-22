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

jimport( 'joomla.application.component.view' );

/**
 * Travelbooks View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class TravelbookViewTravelbook extends JView
{
	/**
	 * Travelbooks view display method
	 * @return void
	 **/
	function display($tpl = null)
	{ 

		global $mainframe;

// Get some objects from the JApplication
		$pathway	=& $mainframe->getPathway();
		$model		= $this->getModel();
//		$user		=& JFactory::getUser();
		$uri     	=& JFactory::getURI();
		$params 	= &$mainframe->getParams();
		$this->assignRef( 'params',					$params);

// Sets Currency
		if ( $params->get( 'currency' ) == 'sonst' ) {
			$currency = $params->get( 'other' );	
		} else {
			$currency = "&".$params->get( 'currency' ).";";	
		}
		$this->assignRef( 'currency',				$currency);	

// Get Menu
		$menu = &JSite::getMenu();
		$arb = $menu->getItem($params->get ('arb'));
		$this->assignRef( 'arb',				$arb);	
		$policy = $menu->getItem($params->get ('policy'));
		$this->assignRef( 'policy',				$policy);	

// Get Files
		$arb_pdf = $params->get('arb_pdf');
		$this->assignRef( 'arb_pdf',				$arb_pdf);	
		$policy_pdf = $params->get('policy_pdf');
		$this->assignRef( 'policy_pdf',				$policy_pdf);	

		$document = &JFactory::getDocument();
		$document->addStyleSheet( 'components/com_travelbook/assets/css/travelbook.css' );
		$document->addStyleSheet( 'components/com_travelbook/assets/css/form.css' );
		$document->addScript( 'components/com_travelbook/assets/js/travelbook.js' );
		$document->addScript( 'components/com_travelbook/assets/js/summary.js' );
		$document->addScript( 'components/com_travelbook/views/travelbook/tmpl/js/ajax.js' );
		$document->addScript('includes/js/joomla.javascript.js');
		$document->addStyleSheet('components/com_travelbook/assets/css/livevalidation.css');
		$document->addScript('components/com_travelbook/assets/js/livevalidation_standalone.js');

		$absolutepath = JURI::root();
		$absolutepath = $absolutepath.'index.php?option=com_travelbook&view=travelbook';

// Get data from the model
		$tb_active_tours =& $this->get( 'tbactivetours' );
		$this->assignRef( 'active_tours_items',				$tb_active_tours);

		$tb_tour =& $this->get( 'tbtour' );
		$this->assignRef( 'tour_items',						$tb_tour);

		$tb_services =& $this->get( 'tbservices' );
		$this->assignRef( 'services_items',					$tb_services);

		$tb_add_services =& $this->get( 'tbaddservices' );
		$this->assignRef( 'add_services_items',				$tb_add_services);

		$tb_hotels =& $this->get( 'tbhotels' );
		$this->assignRef( 'hotels_items',					$tb_hotels);

		$tb_feeders =& $this->get( 'tbfeeders' );
		$this->assignRef( 'feeders',						$tb_feeders);
//var_dump($tb_feeders);

		parent::display($tpl);
	}		

}
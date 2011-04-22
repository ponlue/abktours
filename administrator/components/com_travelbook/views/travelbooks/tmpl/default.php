<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: default.php 2 2010-04-13 13:37:46Z WEB $
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
defined('_JEXEC') or die('Restricted access'); ?>

<form id="adminForm" action="<?php echo JRoute::_( 'index.php?option=com_travelbook&task=travelbooks.show' );?>" method="post" name="adminForm">

<?php JToolBarHelper::preferences( 'com_travelbook',  $height='540', $width='888' ); ?>

<div id="editcell">
	<table class="adminform">
	<tr>
		<td width="66%" valign="top">
			<div id="cpanel">
			<?php
			$link = 'index2.php?option=com_travelbook&amp;task=tours.show';
			$this->_quickiconButton( $link, 'icon-48-tours.png', JText::_('TB Tours'), false  );

			$link = 'index2.php?option=com_travelbook&amp;task=seasons.show';
			$this->_quickiconButton( $link, 'icon-48-seasons.png', JText::_('TB Seasons'), false );

			$link = 'index2.php?option=com_travelbook&amp;task=services.show';
			$this->_quickiconButton( $link, 'icon-48-services.png', JText::_('TB Services'), true );

			$link = 'index2.php?option=com_travelbook&amp;task=hotels.show';
			$this->_quickiconButton( $link, 'icon-48-hotels.png', JText::_('TB Accomodation'), false  );

			$link = 'index2.php?option=com_travelbook&amp;task=feeders.show';
			$this->_quickiconButton( $link, 'icon-48-feeders.png', JText::_('TB Departures'), false  );

			$link = 'index2.php?option=com_travelbook&amp;task=clients.show';
			$this->_quickiconButton( $link, 'icon-48-clients.png', JText::_('TB Clients'), true );

			$link = 'index2.php?option=com_travelbook&amp;task=categories.show';
			$this->_quickiconButton( $link, 'icon-48-categories.png', JText::_('Categories'), true );

			$link = 'index2.php?option=com_travelbook&amp;task=mails.show';
			$this->_quickiconButton( $link, 'icon-48-mails.png', JText::_('TB Mail'), false );
			?>
			</div>
		</td>

		<td width="33%" valign="top" >
		<div style="width: 100%">
		<?php
			$tabs	= $this->get('publishedTabs');
			$pane		=& JPane::getInstance('sliders');
			echo $pane->startPane("content-pane");
	
			foreach ($tabs as $tab) {
				$title = JText::_($tab->title);
				echo $pane->startPanel( $title, 'jfcpanel-panel-'.$tab->name );
				$renderer = 'render' .$tab->name;
				echo $this->$renderer();
				echo $pane->endPanel();
			}
	
			echo $pane->endPane();

		 ?>
		 
		</div>
		</td>

	</tr>
	</table>

</div>

<input type="hidden" name="option" value="com_travelbook" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="travelbook" />
</form>

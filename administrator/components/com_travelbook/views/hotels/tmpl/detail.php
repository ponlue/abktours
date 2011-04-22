<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: detail.php 2 2010-04-13 13:37:46Z WEB $
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

<script language="javascript" type="text/javascript">
<!--
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'hotels.cancel') {
		submitform( pressbutton );
		return;
	}

// do field validation
	if ( form.name.value == "" ) {
		alert( "<?php echo JText::_( 'TB provide hotel title', true ); ?>" );
	} else if ( form.catid.value == 0 ) {
		alert( "<?php echo JText::_( 'TB select Category', true ); ?>" );
	} else {
		submitform( pressbutton );
	}
}
//-->
</script>


<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col width-50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'TB Hotel Details' ); ?></legend>

		<table class="admintable">
		<tr>
			<td class="key">
				<label for="tid">
					<?php echo JText::_( 'ID' ); ?>
				</label>
			</td>
			<td>
				<?php echo $this->hotel->id;?>
				<input type="hidden" name="id" value="<?php echo $this->hotel->id;?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="name">
					<?php echo JText::_( 'TB Hotel Name' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="name" id="name" size="80" maxlength="250" value="<?php echo $this->hotel->name;?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="city">
					<?php echo JText::_( 'TB City' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="city" id="city" size="80" maxlength="250" value="<?php echo $this->hotel->city;?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="catid">
					<?php echo JText::_( 'TB Category' ); ?>
				</label>
			</td>
			<td>
				<?php echo $this->details['catid'];?>
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="rate">
					<?php echo JText::_( 'TB Rate' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="rate" id="rate" size="8" maxlength="8" value="<?php echo number_format( $this->hotel->rate, 2, ',', '.')."&nbsp;".JText::_('TB Currency'); ?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="chain">
					<?php echo JText::_( 'TB Chain' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="chain" id="chain" size="80" maxlength="250" value="<?php echo $this->hotel->chain;?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<?php echo JText::_( 'Published' ); ?>
			</td>
			<td>
				<?php echo $this->details['published']; ?>
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="ordering">
					<?php echo JText::_( 'Ordering' ); ?>
				</label>
			</td>
			<td>
				<?php echo $this->details['ordering']; ?>
			</td>
		</tr>


	</table>
	</fieldset>
</div>

<div class="col width-50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'TB HOTEL INFORMATIONS' ); ?></legend>
		<?php
			$tabs		= $this->get('publishedTabs');
			$pane		=& JPane::getInstance('sliders');
			echo $pane->startPane("content-pane");
			
			foreach ($tabs as $tab) {
				$title = JText::_($tab->title);
				echo $pane->startPanel( $title, 'tb-panel-'.$tab->name );
				$renderer = 'render' .$tab->name;
				echo $this->$renderer();
				echo $pane->endPanel();
			}	
			echo $pane->endPane();
		?>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="com_travelbook" />
<input type="hidden" name="tid" value="<?php echo $this->hotel->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="travelbook" />
<input type="hidden" id="filter_order" name="filter_order" value="" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>

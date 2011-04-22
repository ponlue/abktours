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
	if (pressbutton == 'services.cancel') {
		submitform( pressbutton );
		return;
	}

// do field validation
	if ( form.name.value == "" ) {
		alert( "<?php echo JText::_( 'TB provide service title', true ); ?>" );
	} else if ( form.catid.value == 0 ) {
		alert( "<?php echo JText::_( 'TB select service Category', true ); ?>" );
	} else if ( form.srv_type.value == 0 ) {
		alert( "<?php echo JText::_( 'TB select service Type', true ); ?>" );
	} else {
		submitform( pressbutton );
	}
}
//-->
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col width-50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'TB Service Details' ); ?></legend>

		<table class="admintable">
		<tr>
			<td class="key">
				<label for="tid">
					<?php echo JText::_( 'ID' ); ?>
				</label>
			</td>
			<td>
				<?php echo $this->service->id;?>
				<input type="hidden" name="id" value="<?php echo $this->service->id;?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="title">
					<?php echo JText::_( 'TB Service Name' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="name" id="name" size="80" maxlength="250" value="<?php echo $this->service->name;?>" />
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
					<?php echo JText::_( 'TB Supplement PRO' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="rate" id="rate" size="8" maxlength="8" value="<?php echo $this->service->rate; ?>" />
				<input class="text_area" type="text" name="pro_rata" id="pro_rata" size="16" maxlength="16" value="<?php echo $this->service->pro_rata; ?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="country">
					<?php echo JText::_( 'TB Type' ); ?>
				</label>
			</td>
			<td>
				<?php echo $this->details['srv_type'];?>
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="default">
					<?php echo JText::_( 'TB Default' ); ?>
				</label>
			</td>
			<td>
				<?php echo $this->details['checked']; ?>
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
		<legend><?php echo JText::_( 'TB SERVICE INFORMATIONS' ); ?></legend>
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
<input type="hidden" name="tid" value="<?php echo $this->service->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="travelbook" />
<input type="hidden" id="filter_order" name="filter_order" value="" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>

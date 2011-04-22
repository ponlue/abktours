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
	if (pressbutton == 'clients.cancel') {
		submitform( pressbutton );
		return;
	}

// do field validation
	if ( form.last_name.value == "" ) {
		alert( "<?php echo JText::_( 'TB provide Last Name', true ); ?>" );
	} else if ( form.first_name.value == 0 ) {
		alert( "<?php echo JText::_( 'TB provide First Name', true ); ?>" );
	} else if ( form.title.value == 0 ) {
		alert( "<?php echo JText::_( 'TB provide Title', true ); ?>" );
	} else if ( form.birthdate.value == 0 ) {
		alert( "<?php echo JText::_( 'TB provide Birthdate', true ); ?>" );
	} else {
		submitform( pressbutton );
	}
}
//-->
</script>


<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col width-50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'TB Client Details' ); ?></legend>

		<table class="admintable">
		<tr>
			<td class="key">
				<label for="tid">
					<?php echo JText::_( 'ID' ); ?>
				</label>
			</td>
			<td>
				<?php echo $this->client->id;?>
				<input type="hidden" name="id" value="<?php echo $this->client->id;?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="title">
					<?php echo JText::_( 'TB Title' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="title" id="title" size="8" maxlength="8" value="<?php echo $this->client->title;?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="last_name">
					<?php echo JText::_( 'TB Last Name' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="last_name" id="last_name" size="32" maxlength="80" value="<?php echo $this->client->last_name;?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="first_name">
					<?php echo JText::_( 'TB First Name' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="first_name" id="first_name" size="32" maxlength="80" value="<?php echo $this->client->first_name;?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="birthdate">
					<?php echo JText::_( 'TB Birthdate' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="birthdate" id="birthdate" size="10" maxlength="10" value="<?php echo JHTML::_('date', $this->client->birthdate, JText::_('DATE_FORMAT_TB1'));?>" />
				<script type="text/javascript">
					window.addEvent('domready', function() { myCal = new Calendar({ birthdate: 'd-m-Y' },  { navigation: 2 }); });
				</script>	
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="street">
					<?php echo JText::_( 'TB Street' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="street" id="street" size="32" maxlength="80" value="<?php echo $this->client->street; ?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="cip">
					<?php echo JText::_( 'TB CIP City' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="cip" id="cip" size="5" maxlength="5" value="<?php echo $this->client->cip; ?>" />
				<input class="text_area" type="text" name="city" id="city" size="27" maxlength="80" value="<?php echo $this->client->city; ?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="country">
					<?php echo JText::_( 'TB Country' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="country" id="country" size="80" maxlength="250" value="<?php echo $this->client->country; ?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="telefone">
					<?php echo JText::_( 'TB Fon' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="telefone" id="telefone" size="80" maxlength="250" value="<?php echo $this->client->telefone; ?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="fax">
					<?php echo JText::_( 'TB FAX' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="fax" id="fax" size="80" maxlength="250" value="<?php echo $this->client->fax; ?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="mobile">
					<?php echo JText::_( 'TB Mobile' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="mobile" id="mobile" size="80" maxlength="250" value="<?php echo $this->client->mobile; ?>" />
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

		<tr>
			<td class="key">
				<label for="ip">
					<?php echo JText::_( 'IP' ); ?>
				</label>
			</td>
			<td>
				<?php echo $this->client->ip;?>
				<input type="hidden" name="ip" value="<?php echo $this->client->ip;?>" />
			</td>
		</tr>



	</table>
	</fieldset>
</div>

<div class="col width-50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'TB CLIENT INFORMATIONS' ); ?></legend>
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
<input type="hidden" name="tid" value="<?php echo $this->client->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="travelbook" />
<input type="hidden" id="filter_order" name="filter_order" value="" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>

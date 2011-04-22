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
defined('_JEXEC') or die('Restricted access'); 

$editor =& JFactory::getEditor(); ?>

<script language="javascript" type="text/javascript">
<!--
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'mails.cancel') {
		submitform( pressbutton );
		return;
	}

// do field validation
	var text = <?php echo $editor->getContent( 'mailbody' ); ?>
	if ( form.subject.value == '' ) {
		alert( "<?php echo JText::_( 'TB provide Subject', true ); ?>" );
	} else if ( form.from.value == '' ) {
		alert( "<?php echo JText::_( 'TB provide From', true ); ?>" );
	} else if ( form.fromname.value == '' ) {
		alert( "<?php echo JText::_( 'TB provide Fromname', true ); ?>" );
	} else if ( text == '' ) {
		alert( "<?php echo JText::_( 'TB provide Body', true ); ?>" );
	} else {
		submitform( pressbutton );
	}
}
//-->
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col width-60">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'TB Mail Details' ); ?></legend>

		<table class="admintable">
		<tr>
			<td class="key">
				<label for="tid">
					<?php echo JText::_( 'ID' ); ?>
				</label>
			</td>
			<td>
				<?php echo $this->mail->id;?>
				<input type="hidden" name="id" value="<?php echo $this->mail->id;?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="subject">
					<?php echo JText::_( 'TB Subject' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="subject" id="subject" size="49" maxlength="49" value="<?php echo $this->mail->subject;?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="mailbody">
					<?php echo JText::_( 'TB Body' ); ?>
				</label>
			</td>
			<td>
				<?php
					$editor =& JFactory::getEditor();
					echo $editor->display ('mailbody', $this->mail->mailbody, '550', '400', '60', '20', false);
				?>
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="from">
					<?php echo JText::_( 'TB From' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="from" id="from" size="64" maxlength="64" value="<?php echo $this->mail->from;?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="fromname">
					<?php echo JText::_( 'TB Fromname' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="fromname" id="fromname" size="64" maxlength="64" value="<?php echo $this->mail->fromname;?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="cc">
					<?php echo JText::_( 'TB cc' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="cc" id="cc" size="64" maxlength="64" value="<?php echo $this->mail->cc;?>" />
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="bcc">
					<?php echo JText::_( 'TB bcc' ); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="bcc" id="bcc" size="64" maxlength="64" value="<?php echo $this->mail->bcc;?>" />
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


	</table>
	</fieldset>
</div>

<div class="col width-40">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'TB SUPPORT INFORMATIONS' ); ?></legend>
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
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="travelbook" />
<input type="hidden" id="filter_order" name="filter_order" value="" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>

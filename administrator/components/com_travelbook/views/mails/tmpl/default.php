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
<form id="adminForm" action="<?php echo JRoute::_( 'index.php?option=com_travelbook&task=mails.show' );?>" method="post" name="adminForm">
<div id="editcell">

	<table>
	<tr>
		<td align="left" width="100%">
			<?php echo JText::_( 'Filter' ); ?>:
			<input type="text" name="search" id="search" value="<?php echo @$this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
		</td>
		<td nowrap="nowrap">
			<?php
			echo $this->lists['state'];
			?>
		</td>
	</tr>
	</table>

<?php global $pageNav; ?>
<?php $ordering = ($this->lists['order'] == 'ordering'); ?>
<?php 
	$saveorder = JHTML::_('grid.order',  $this->mails );
	$saveorder = str_replace("'saveorder'", "'mails.saveorder'", $saveorder); 
?>
	<table class="adminlist">
	<thead>
		<tr class="sortable">
			<th width="10"><?php echo JText::_( 'Num' ); ?></th>			
			<th width="10">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->mails ); ?>);" />
			</th>			
			<th>
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Subject' ),  'subject', 	@$this->lists['order_Dir'],  @$this->lists['order'] ); ?>		
			</th>
			<th>
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB From' ),  'from', 	@$this->lists['order_Dir'],  @$this->lists['order'] ); ?>		
			</th>
			<th>
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB fromname' ),  'fromname', 	@$this->lists['order_Dir'],  @$this->lists['order'] ); ?>		
			</th>

			<th width="12%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   JText::_( 'Published' ), 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>
			<th width="10">
				<?php echo JHTML::_('grid.sort',   JText::_( 'ID' ), 'id', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>
		</tr>
	</thead>
			<tfoot>
				<tr>
					<td colspan="7">
						<?php echo $pageNav->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
	<?php


for ($i=0, $n=count($this->mails); $i < $n; $i++){
  $mail = &$this->mails[$i];
		$published 	= JHTML::_('grid.published', $mail, $i );
		$published = str_replace("'publish'", "'mails.publish'", $published);
		$published = str_replace("'unpublish'", "'mails.unpublish'", $published);

		$checked 	= JHTML::_('grid.id',   $i, $mail->id );
		$link 		= JRoute::_( 'index.php?option=com_travelbook&task=mails.detail&tid[]='. $mail->id );
		echo "	<tr class='row".$mail->id."'>\n";
// #
		echo "		<td>\n";
		echo $pageNav->getRowOffset( $i );
		echo "		</td>\n";
// checkbox
		echo "		<td>\n";
		echo $checked;
		echo "		</td>\n";
// subject
		echo "		<td>\n";
		echo "		<a href='".$link."'>";  echo $mail->subject;  
		echo " </a>
			</td>";
// from
		echo "		<td>\n";
		echo $mail->from;
		echo "		</td>\n";
// fromname
		echo "		<td>\n";
		echo $mail->fromname;
		echo "		</td>\n";
// activ
		echo "	<td align='center'>".$published."</td>";
// ID
		echo "		<td>\n";
		echo $mail->id;
		echo "		</td>\n";
		echo "</tr>";
	}
	?>
	</table>

</div>

<input type="hidden" name="option" value="com_travelbook" />
<input type="hidden" name="task" value="mails.show" /> 
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="mails" />
<input type="hidden" id="filter_order" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
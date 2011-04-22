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
<form id="adminForm" action="<?php echo JRoute::_( 'index.php?option=com_travelbook&task=clients.show' );?>" method="post" name="adminForm">
<div id="editcell">

	<table>
	<tr>
		<td align="left" width="100%">
			<?php echo JText::_( 'Filter' ); ?>:
			<input type="text" name="search" id="search" value="<?php echo @$this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.getElementById('filter_order').value='clients.ordering';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
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
	$saveorder = JHTML::_('grid.order',  $this->clients );
	$saveorder = str_replace("'saveorder'", "'clients.saveorder'", $saveorder); 
?>
	<table class="adminlist">
	<thead>
		<tr class="sortable">
			<th width="10"><?php echo JText::_( 'Num' ); ?></th>			
			<th width="10">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->clients ); ?>);" />
			</th>			
			<th>
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Last Name' ),  'last_name', 	@$this->lists['order_Dir'],  @$this->lists['order'] ); ?>		
			</th>
			<th>
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB First Name' ),  'first_name', 	@$this->lists['order_Dir'],  @$this->lists['order'] ); ?>		
			</th>
			<th>
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Title' ),  'title', 	@$this->lists['order_Dir'],  @$this->lists['order'] ); ?>		
			</th>

			<th>
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Birthdate' ),  'birthdate', 	@$this->lists['order_Dir'],  @$this->lists['order'] ); ?>		
			</th>

			<th>
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB CIP' ),  'cip', 	@$this->lists['order_Dir'],  @$this->lists['order'] ); ?>		
			</th>

			<th>
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB City' ),  'city', 	@$this->lists['order_Dir'],  @$this->lists['order'] ); ?>		
			</th>

			<th width="12%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   JText::_( 'Published' ), 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>
			<th width="10%">
				<?php echo JHTML::_('grid.sort',   JText::_( 'Order by' ), 'ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
				<?php if ($ordering) echo $saveorder; ?>
			</th>

			<th width="10">
				<?php echo JHTML::_('grid.sort',   JText::_( 'ID' ), 'id', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>
		</tr>
	</thead>
			<tfoot>
				<tr>
					<td colspan="11">
						<?php echo $pageNav->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
	<?php


for ($i=0, $n=count($this->clients); $i < $n; $i++){
  $client = &$this->clients[$i];
		$published 	= JHTML::_('grid.published', $client, $i );
		$published = str_replace("'publish'", "'clients.publish'", $published);
		$published = str_replace("'unpublish'", "'clients.unpublish'", $published);

		$checked 	= JHTML::_('grid.id',   $i, $client->id );
		$link 		= JRoute::_( 'index.php?option=com_travelbook&task=clients.detail&tid[]='. $client->id );
		echo "	<tr class='row".$client->id."'>\n";
// #
//		echo "		<td>\n";
		echo "		<td>\n";
		echo $pageNav->getRowOffset( $i );
		echo "		</td>\n";
//		echo $i;
//		echo "		</td>\n";
// checkbox
		echo "		<td>\n";
		echo $checked;
		echo "		</td>\n";
// last name
		echo "		<td>\n";
		echo "		<a href='".$link."'>";  echo $client->last_name;  
		echo " </a>
			</td>";
// first name
		echo "		<td>\n";
		echo $client->first_name;
		echo "		</td>\n";
// title
		echo "		<td>\n";
		echo $client->title;
		echo "		</td>\n";
// birthdate
		echo "		<td>\n";
		echo $client->birthdate;
		echo "		</td>\n";
// cip
		echo "		<td>\n";
		echo $client->cip;
		echo "		</td>\n";
// city
		echo "		<td>\n";
		echo $client->city;
		echo "		</td>\n";
// activ
		echo "	<td align='center'>".$published."</td>";
// order
		echo "		<td class='order'>\n";
		echo "	<span>".$pageNav->orderUpIcon( $i, ( $client->catid == @$this->clients[$i-1]->catid ), 'clients.orderup', 'Move Up', $ordering )."</span>";
		echo "	<span>".$pageNav->orderDownIcon( $i, $n, ( $client->catid == @$this->clients[$i+1]->catid ), 'clients.orderdown', 'Move Down', $ordering )."</span>";
		$disabled = $ordering ?  '' : 'disabled="disabled"';
		echo "	<input type='text' name='order[]' size='5' value='".$client->ordering."' ".$disabled." class='text_area' style='text-align: center' />";
		echo "		</td>\n";
// ID
		echo "		<td>\n";
		echo $client->id;
		echo "		</td>\n";
			
		echo "</tr>";
	}
	?>
	</table>

</div>

<input type="hidden" name="option" value="com_travelbook" />

<input type="hidden" name="task" value="clients.show" /> 
<input type="hidden" name="boxchecked" value="0" />
<!-- 
<input type="hidden" name="tid[]" value="<?php //echo $client->id; ?>" />
-->

<input type="hidden" name="controller" value="clients" />

<input type="hidden" id="filter_order" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
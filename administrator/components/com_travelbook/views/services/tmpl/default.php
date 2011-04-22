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
<form id="adminForm" action="<?php echo JRoute::_( 'index.php?option=com_travelbook&task=services.show' );?>" method="post" name="adminForm">
<div id="editcell">

	<table>
	<tr>
		<td align="left" width="100%">
			<?php echo JText::_( 'Filter' ); ?>:
			<input type="text" name="search" id="search" value="<?php echo @$this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_catid').value='0';this.form.getElementById('filter_srv_type').value='0';this.form.getElementById('filter_state').value='';this.form.getElementById('filter_order').value='services.ordering';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
		</td>
		<td nowrap="nowrap">
			<?php
			echo $this->lists['srv_type'];
			echo $this->lists['catid'];
			echo $this->lists['state'];
			?>
		</td>
	</tr>
	</table>

<?php global $pageNav; ?>
<?php $ordering = ($this->lists['order'] == 'ordering'); ?>
<?php 
	$saveorder = JHTML::_('grid.order',  $this->services );
	$saveorder = str_replace("'saveorder'", "'services.saveorder'", $saveorder); 
?>
	<table class="adminlist">
	<thead>
		<tr class="sortable">
			<th width="10"><?php echo JText::_( 'Num' ); ?></th>			
			<th width="10">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->services ); ?>);" />
			</th>			
			<th>
				<?php echo JHTML::_('grid.sort',   JText::_( 'Service' ),  'name', 	@$this->lists['order_Dir'],  @$this->lists['order'] ); ?>		
			</th>
			<th width="10%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   JText::_( 'Type' ),  'srv_type', 	@$this->lists['order_Dir'],  @$this->lists['order'] ); ?>		
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   JText::_( 'Published' ), 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>
			<th width="10%">
				<?php echo JHTML::_('grid.sort',   JText::_( 'Order by' ), 'ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
				<?php if ($ordering) echo $saveorder; ?>
			</th>

			<th width="8%">
				<?php echo JHTML::_('grid.sort',   JText::_( 'Category' ), 'title', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>

			<th width="8%">
				<?php echo JHTML::_('grid.sort',   JText::_( 'Rate' ), 'rate', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>

			<th width="10">
				<?php echo JHTML::_('grid.sort',   JText::_( 'ID' ), 'id', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>
		</tr>
	</thead>
			<tfoot>
				<tr>
					<td colspan="10">
						<?php echo $pageNav->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
	<?php


for ($i=0, $n=count($this->services); $i < $n; $i++){
  $service = &$this->services[$i];
		$published 	= JHTML::_('grid.published', $service, $i );
		$published = str_replace("'publish'", "'services.publish'", $published);
		$published = str_replace("'unpublish'", "'services.unpublish'", $published);

		$checked 	= JHTML::_('grid.id',   $i, $service->id );
		$link 		= JRoute::_( 'index.php?option=com_travelbook&task=services.detail&cid[]='. $service->id );
		echo "	<tr class='row".$service->id."'>\n";
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
// name
		echo "		<td>\n";
		echo "		<a href='".$link."'>";  echo $service->name;  
		echo " </a>
			</td>";
// type
		echo "		<td>\n";
		echo $service->typ;
		echo "		</td>\n";
// activ
		echo "	<td align='center'>".$published."</td>";
// order
		echo "		<td class='order'>\n";
		echo "	<span>".$pageNav->orderUpIcon( $i, ( $service->title == @$this->services[$i-1]->title ), 'services.orderup', 'Move Up', $ordering )."</span>";
		echo "	<span>".$pageNav->orderDownIcon( $i, $n, ( $service->title == @$this->services[$i+1]->title ), 'services.orderdown', 'Move Down', $ordering )."</span>";
		$disabled = $ordering ?  '' : 'disabled="disabled"';
		echo "	<input type='text' name='order[]' size='5' value='".$service->ordering."' ".$disabled." class='text_area' style='text-align: center' />";
		echo "		</td>\n";
// type
		echo "		<td>\n";
		echo $service->title;
		echo "		</td>\n";
// rate
		echo "		<td>\n";
		echo $service->rate;
		echo "		</td>\n";
// ID
		echo "		<td>\n";
		echo $service->id;
		echo "		</td>\n";
			
		echo "</tr>";
	}
	?>
	</table>

</div>

<input type="hidden" name="option" value="com_travelbook" />
<input type="hidden" name="task" value="services.show" /> 
<input type="hidden" name="boxchecked" value="0" />
<!-- 
<input type="hidden" name="tid[]" value="<?php //echo $service->id; ?>" />
-->
<input type="hidden" name="controller" value="services" />
<input type="hidden" id="filter_order" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
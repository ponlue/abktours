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
<form id="adminForm" action="<?php echo JRoute::_( 'index.php?option=com_travelbook&task=seasons.show' );?>" method="post" name="adminForm">
<div id="editcell">

	<table>
	<tr>
		<td align="left" width="100%">
			<?php echo JText::_( 'Filter' ); ?>:
			<input type="text" name="search" id="search" value="<?php echo @$this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_catid').value='';this.form.getElementById('filter_state').value='';this.form.getElementById('filter_order').value='seasons.ordering';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
		</td>
		<td nowrap="nowrap">
			<?php
			echo $this->lists['clist'];
			echo $this->lists['state'];
			?>
		</td>
	</tr>
	</table>

<?php global $pageNav; ?>
<?php $ordering = ($this->lists['order'] == 'ordering'); ?>
<?php 
	$saveorder = JHTML::_('grid.order',  $this->seasons );
	$saveorder = str_replace("'saveorder'", "'seasons.saveorder'", $saveorder); 
?>
	<table class="adminlist">
	<thead>
		<tr class="sortable">
			<th width="10"><?php echo JText::_( 'Num' ); ?></th>			
			<th width="10">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->seasons ); ?>);" />
			</th>			
			<th  width="16%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Departure' ), 'departure', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
			</th>
			<th  width="16%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Arrival' ), 'arrival', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
			</th>
			<th  width="6%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Duration' ), 'duration', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
			</th>
			<th>
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Tour' ),  'title', 	@$this->lists['order_Dir'],  @$this->lists['order'] ); ?>		
			</th>
			<th width="4%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   JText::_( 'Published' ), 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>
			<th width="10%">
				<?php echo JHTML::_('grid.sort',   JText::_( 'Order by' ), 'ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
				<?php if ($ordering) echo $saveorder; ?>
			</th>

			<th width="8%">
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Rate' ), 'rate', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>
			<th width="1%">
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Available' ), 'available', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>

			<th width="8%">
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Single' ), 'single_supplement', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>

			<th width="10">
				<?php echo JHTML::_('grid.sort',   JText::_( 'ID' ), 'id', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>
		</tr>
	</thead>
			<tfoot>
				<tr>
					<td colspan="12">
						<?php echo $pageNav->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
	<?php


for ($i=0, $n=count($this->seasons); $i < $n; $i++){
  $season = &$this->seasons[$i];
		$published 	= JHTML::_('grid.published', $season, $i );
		$published = str_replace("'publish'", "'seasons.publish'", $published);
		$published = str_replace("'unpublish'", "'seasons.unpublish'", $published);

		$checked 	= JHTML::_('grid.id',   $i, $season->id );
		$link 		= JRoute::_( 'index.php?option=com_travelbook&task=seasons.detail&tid[]='. $season->id );
		$link_tour	= JRoute::_( 'index.php?option=com_travelbook&task=tours.detail&history=seasons.cancel&tid[]='. $season->jrn_id );
		echo "	<tr class='row".$season->id."'>\n";
// #
		echo "		<td>\n";
		echo $pageNav->getRowOffset( $i );
		echo "		</td>\n";
// checkbox
		echo "		<td>\n";
		echo $checked;
		echo "		</td>\n";
// departure
		echo "		<td>\n";
		echo "		<a href='".$link."'>";
		echo JHTML::_('date', $season->departure, JText::_('DATE_FORMAT_LC1'));
		echo " </a>";
		echo "		</td>\n";
// arrival
		echo "		<td>\n";
		echo JHTML::_('date', $season->arrival, JText::_('DATE_FORMAT_LC1'));
		echo "		</td>\n";
// duration
		echo "		<td align='center'>\n";
		echo $season->duration;
		echo "		</td>\n";
// tour
		echo "		<td>\n";
		echo "		<a href='".$link_tour."'>";  echo $season->title;  
		echo " </a>
			</td>";
// activ
		echo "	<td align='center'>".$published."</td>";
// order
		echo "		<td class='order'>\n";
		echo "	<span>".$pageNav->orderUpIcon( $i, ( $season->title == @$this->seasons[$i-1]->title ), 'seasons.orderup', 'Move Up', $ordering )."</span>";
		echo "	<span>".$pageNav->orderDownIcon( $i, $n, ( $season->title == @$this->seasons[$i+1]->title ), 'seasons.orderdown', 'Move Down', $ordering )."</span>";
		$disabled = $ordering ?  '' : 'disabled="disabled"';
		echo "	<input type='text' name='order[]' size='5' value='".$season->ordering."' ".$disabled." class='text_area' style='text-align: center' />";
		echo "		</td>\n";
// year
		echo "		<td align='right'>\n";
		echo number_format($season->rate, 0, ',', '.')."&nbsp;".JText::_('TB Currency');
		echo "		</td>\n";
// type
		echo "		<td align='center'>\n";
		echo $season->available;
		echo "		</td>\n";
// country
		echo "		<td align='right'>\n";
		echo number_format($season->single_supplement, 0, ',', '.')."&nbsp;".JText::_('TB Currency');
		echo "		</td>\n";
// ID
		echo "		<td>\n";
		echo $season->id;
		echo "		</td>\n";
			
		echo "</tr>";
	}
	?>
	</table>

</div>

<input type="hidden" name="option" value="com_travelbook" />
<input type="hidden" name="task" value="seasons.show" /> 
<input type="hidden" name="boxchecked" value="0" />
<!-- 
<input type="hidden" name="tid[]" value="<?php //echo $season->id; ?>" />
-->
<input type="hidden" name="controller" value="seasons" />
<input type="hidden" id="filter_order" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
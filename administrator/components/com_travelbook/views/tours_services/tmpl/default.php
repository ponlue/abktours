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
<form id="adminForm" action="<?php echo JRoute::_( 'index.php?option=com_travelbook&task=tours.addservices' );?>" method="post" name="adminForm">
<div id="editcell">
	<table>
	<tr>
		<td align="left" width="100%">
			<?php echo JText::_( 'Filter' ); ?>:
			<input type="text" name="search" id="search" value="<?php echo @$this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_catid').value='0';this.form.getElementById('filter_srv_type').value='0';this.form.getElementById('filter_state').value='';this.form.getElementById('filter_order').value='tour_services.ordering';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
		</td>
		
		<td nowrap="nowrap">
			<?php
			echo $this->lists['catid'];
			echo $this->lists['state'];
			?>
		</td>
	</tr>
	</table>

<div class="col width-50">

<?php global $pageNav; ?>
<?php $ordering = ($this->lists['order'] == 'tour_services.ordering'); ?>

<?php echo "<div class='header'><small>".JText::_( 'TB Services linked' )."</small></div>"; ?>
	<table class="adminlist">
	<thead>
		<tr class="sortable">
			<th width="10"><?php echo JText::_( 'Num' ); ?></th>			
			<th width="10">
				<input type="checkbox" name="toggle0" value="" onclick="if (document.adminForm.toggle1.checked) {document.adminForm.toggle1.name='toggle'; document.adminForm.toggle.checked=false; checkAll(<?php echo count( $this->services_inv ); ?>, 'cb_inv'); document.adminForm.toggle.name='toggle1';};  document.adminForm.toggle0.name='toggle'; checkAll(<?php echo count( $this->services ); ?>, 'cb'); document.adminForm.toggle.name='toggle0';" />
			</th>			
			<th>
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Service' ),  'name', 	@$this->lists['order_Dir'],  @$this->lists['order'] ); ?>		
			</th>
			<th width="10%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Type' ),  'type', 	@$this->lists['order_Dir'],  @$this->lists['order'] ); ?>		
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   JText::_( 'Published' ), 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>
			<th width="8%">
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Category' ), 'category', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>
			<th width="8%">
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Rate Service' ), 'rate', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
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
		$published = str_replace("'publish'", "'tours.publishTourService'", $published);
		$published = str_replace("'unpublish'", "'tours.unpublishTourService'", $published);

		$checked 	= JHTML::_('grid.id',   $i, $service->id );
		$link 		= JRoute::_( 'index.php?option=com_travelbook&task=services.detail&cid[]='. $service->srv_id );
		echo "	<tr class='row".$service->id."'>\n";
// #
		echo "		<td>\n";
		echo $pageNav->getRowOffset( $i );
		echo "		</td>\n";
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
		echo $service->type;
		echo "		</td>\n";
// activ
		if ( $service->published == 0) {
			echo "	<td align='center'><img src='images/publish_x.png' border='0' alt='Gesperrt' /></td>";
		} else {
			echo "	<td align='center'><img src='images/tick.png' border='0' alt='Freigegeben' /></td>";
		}
// categroy
		echo "		<td>\n";
		echo $service->category;
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

<div class="col width-50">

<?php global $pageNav_inv; ?>
<?php echo "<div class='header'><small>".JText::_( 'TB Services NOT linked' )."</small></div>"; ?>
	<table class="adminlist">
	<thead>
		<tr class="sortable">
			<th width="10"><?php echo JText::_( 'Num' ); ?></th>			
			<th width="10">
				<input type="checkbox" name="toggle1" value="" onclick="if (document.adminForm.toggle0.checked) {document.adminForm.toggle0.name='toggle'; document.adminForm.toggle.checked=false; checkAll(<?php echo count( $this->services ); ?>, 'cb'); document.adminForm.toggle.name='toggle0';};  document.adminForm.toggle1.name='toggle'; checkAll(<?php echo count( $this->services_inv ); ?>, 'cb_inv'); document.adminForm.toggle.name='toggle1';" />
			</th>			
			<th>
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Service' ),  'name', 	@$this->lists['order_Dir'],  @$this->lists['order'] ); ?>		
			</th>
			<th width="10%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Type' ),  'srv_type', 	@$this->lists['order_Dir'],  @$this->lists['order'] ); ?>		
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   JText::_( 'Published' ), 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>
			<th width="8%">
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Category' ), 'title', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>
			<th width="8%">
				<?php echo JHTML::_('grid.sort',   JText::_( 'TB Rate Service' ), 'rate', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>
			<th width="10">
				<?php echo JHTML::_('grid.sort',   JText::_( 'ID' ), 'id', $this->lists['order_Dir'], $this->lists['order'] ); ?>		
			</th>
		</tr>
	</thead>
			<tfoot>
				<tr>
					<td colspan="10">
					
						<?php echo str_replace ('limitstart', 'limitstart_inv', str_replace ('"limit"', '"limit_inv"', $pageNav_inv->getListFooter())); ?>
					</td>
				</tr>
			</tfoot>
	<?php


for ($i=0, $n=count($this->services_inv); $i < $n; $i++){
  $service = &$this->services_inv[$i];
		$published 	= JHTML::_('grid.published', $service, $i );
		$published = str_replace("'publish'", "'tours.publishTourService_inv'", $published );
		$published = str_replace("'unpublish'", "'tours.unpublishTourService_inv'", $published );
		$published = str_replace('cb', 'cb_inv', $published);

		$checked 	= JHTML::_('grid.id', $i, $service->id );
		$link 		= JRoute::_( 'index.php?option=com_travelbook&task=services.detail&cid[]='. $service->srv_id );
		echo "	<tr class='row".$service->id."'>\n";
// #
		echo "		<td>\n";
		echo $pageNav->getRowOffset( $i );
		echo "		</td>\n";
// checkbox
		echo "		<td>\n";
		echo str_replace('cid[]', 'cid_inv[]', str_replace('cb', 'cb_inv', $checked));
		echo "		</td>\n";
// name
		echo "		<td>\n";
		echo "		<a href='".$link."'>";  echo $service->name;  
		echo " </a>
			</td>";
// type
		echo "		<td>\n";
		echo $service->type;
		echo "		</td>\n";
// activ
		echo "	<td align='center'>".$published."</td>";
// category
		echo "		<td>\n";
		echo $service->category;
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
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_travelbook" />
<input type="hidden" name="task" value="tours.addservices" /> 
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="tid" value="<?php echo $this->tour->id; ?>" />
<input type="hidden" name="controller" value="services" />
<input type="hidden" id="filter_order" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
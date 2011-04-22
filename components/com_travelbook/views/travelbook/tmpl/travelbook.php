<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: travelbook.php 2 2010-04-13 13:37:46Z WEB $
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

<form action="index.php?option=com_travelbook&amp;task=apply" method="post" name="adminForm" id="adminForm">
	<div class="col100">
		<div class="travelbook">
			<?php 
			
			// Show Image
				echo "<div id='ajax_img'>\n";
				if ($this->params->get( 'image' )) {
					echo "	<img src='".$this->tour_items[0]->picture."' alt='".$this->tour_items[0]->title."' />\n";
				}
				echo "</div>\n";

			// Show Title
				echo "<h1 class='contentheading' id='ajax_title'>\n";
				if ($this->params->get( 'title' )) {
					echo $this->tour_items[0]->title."\n";
				}
				echo "</h1>\n";
			?>
			<!-- PAGE FORM ONE -->
			<div id="mainForm_1"> 
				<ul class="tabs-nav">
					<li class="current"><a name="Termin"></a><?php echo JText::_('TB Terminauswahl');?></li>
					<li class="inactive"><a name="Service"></a><?php echo JText::_('TB Leistungen');?></li>
					<li class="inactive"><a name="Paxe"></a><?php echo JText::_('TB Reiseteilnehmer');?></li>
					<li class="inactive"><a name="Kontakt"></a><?php echo JText::_('TB Kontaktdaten');?></li>
					<li class="inactive"><a name="Summary"></a><?php echo JText::_('TB Summary Application');?></li>
				</ul>
				<br />
				<?php 
					if ($this->params->get( 'contact' ) && $this->params->get( 'telefon' )) {
						echo "<strong>\n"; 
						echo JText::_('TB Sie haben Fragen zur Buchung')."<br />".JText::sprintf('TB Wir stehen Ihnen',$this->params->get( 'telefon' ));
						echo "</strong>\n";
					}
				?>
			
			<!-- PAGE ONE -->
			<?php
			// Show Subtitle
				echo "	<div id='ajax_sub_title'>\n";
				echo "		<h2>".JText::_('TB Choose your Trip')."</h2>\n	</div>\n";
				echo "	<fieldset>\n";
				echo "		<legend>".JText::_('TB Trip')."</legend>\n";

			// Show Tour Selector
				echo "		<div id='ajax_tour'>\n";
				echo "			<label for='tour' class='required extra' accesskey='s'>".JText::_('TB Journey').": </label>\n";
				echo "			<select class='page1' name='tour' size='1' id='tour' onchange='loadfields(\"99\",\"".JText::_( 'TB Status ready', true )."\",\"".JText::_( 'TB One moment', true )."\")'>\n"; 
				foreach ( $this->active_tours_items as $record ) {
					if ($record->id == $this->tour_items[0]->tour_id) {
						echo "				<option value='".$record->id."' selected='selected'>".$record->title."</option>\n";
					} else {
						echo "				<option value='".$record->id."'>".$record->title."</option>\n";
					}
				}
				echo "			</select>\n";
				echo "		</div>\n";
					
			// Show Default Pax Selector
				echo "		<div id='ajax_pax'>\n";
				echo "			<label for='pax' class='required extra'>".JText::_('TB Number Traveller').": </label>\n";
				echo "			<select class='page1' name='pax' size='1' id='pax' onchange='loadfields(\"0\",\"".JText::_( 'TB Status ready', true )."\",\"".JText::_( 'TB One moment', true )."\")'>\n";
				echo "				<option value='1'>".JText::sprintf('TB Erwachsener', 1)."</option>\n";
				echo "				<option value='2' selected='selected'>".JText::sprintf('TB Erwachsene', 2)."</option>\n";
				echo "				<option value='3'>".JText::sprintf('TB Erwachsene', 3)."</option>\n";
				echo "				<option value='4'>".JText::sprintf('TB Erwachsene', 4)."</option>\n";
				echo "				<option value='5'>".JText::sprintf('TB Erwachsene', 5)."</option>\n";
				echo "				<option value='6'>".JText::sprintf('TB Erwachsene', 6)."</option>\n";
				echo "				<option value='7'>".JText::sprintf('TB Erwachsene', 7)."</option>\n";
				echo "				<option value='8'>".JText::sprintf('TB Erwachsene', 8)."</option>\n";
				echo "			</select>\n";
				echo "		</div>\n";
				
				if (($this->params->get( 'feeder' )) AND ($this->feeders)) {
			// Show Feeder Selector
					$dat_id  = $this->tour_items[0]->dat_id;
					echo "		<div id='ajax_feeder'>\n";
					echo "			<label for='feeder' class='required extra' accesskey='a'>".JText::_('TB Feeder').": </label>\n";
					echo "			<select class='page1' name='feeder' size='1' id='feeder' onchange='loadfields(\"87\",\"".JText::_( 'TB Status ready', true )."\",\"".JText::_( 'TB One moment', true )."\")'>\n";
					$total = $total + 2*$this->feeders[0]->markup;
					foreach ( $this->feeders as $feeder ) {
						if ( ($dat_id == $feeder->dat_id) ){
							echo "        <option value='".$feeder->id."'>".$feeder->name."</option>"; 
						}
					}
					echo "			</select>\n";
					echo "		</div>\n";
				} else {
					echo "		<div id='ajax_feeder'>\n";
					echo "		<div id='feeder' value='0'></div>\n";
					echo "		</div>\n";
				}
				
				if (($this->params->get( 'hotel_select' )) AND ($this->tour_items[0]->select_hotel) AND ($this->hotels_items)) {
			// Show Hotel Selector
					$jrn_id  = $this->tour_items[0]->tour_id;
					echo "		<div id='ajax_accomodation'>\n";
					echo "			<label for='accomodation' class='required extra' accesskey='h'>".JText::_('TB Accomodation').": </label>\n";
					echo "			<select class='page1' name='accomodation' size='1' id='accomodation' onchange='loadfields(\"86\",\"".JText::_( 'TB Status ready', true )."\",\"".JText::_( 'TB One moment', true )."\")'>\n";
					$total = $total + $this->hotels_items[0]->hotel_rate*$this->hotels_items[0]->accomodation;
					foreach ( $this->hotels_items as $hotel ) {
						echo "        <option value='".$hotel->id."'>".$hotel->name." ".$hotel->category." ".$hotel->chain."</option>"; 
					}
					echo "			</select>\n";
					echo "		</div>\n";
				} else {
					echo "		<div id='ajax_accomodation'>\n";
					echo "		<div id='accomodation' value='0'></div>\n";
					echo "		</div>\n";
				}

			// Show Seasons
				echo "		<div id='ajax_season'>\n";
				echo "			<table cellpadding='3' width='100%'>
							<thead class='tb'>
								<tr>
									<th style='width: 11%;' class='tb_left' colspan='2'>".JText::_('TB Date')."</th>
									<th style='width: 35%;' class='tb_right'>".JText::sprintf('TB Doppelzimmer2', '')."</th>
									<th style='width: 30%;' class='tb_right'>".JText::sprintf('TB Einzelzimmer2', '')."</th>";

				if (($this->params->get( 'feeder' )) AND ($this->feeders)) {
					echo "<th style='width: 12%;' class='tb_right'>".JText::sprintf('TB Feeder2', '')."</th>";
				}
				if (($this->params->get( 'hotel_select' )) AND ($this->tour_items[0]->select_hotel)) {
					echo "<th style='width: 12%;' class='tb_right'>".JText::sprintf('TB Hotel2', '')."</th>";
				}

				echo "			</tr>
							</thead>
							<tbody>\n";
			
						$id  = '';
						$first = true;
						foreach ( $this->tour_items as $record ) {
								list($year_dep, $month_dep, $day_dep) = explode("-", $record->departure);
								list($year_arr, $month_arr, $day_arr) = explode("-", $record->arrival);
								
								echo "					<tr"; 
								if ( ($record->dat_id != '') AND ($first) ){
									echo " class='season_selected' ";
								}				
									echo ">\n";
									echo "						<td class='tb_left'>\n";
									echo "							<input name='season' value='".$record->dat_id."' onclick='loadfields(\"88\",\"".JText::_( 'TB Status ready', true )."\",\"".JText::_( 'TB One moment', true )."\")' ";
								if ( ($record->dat_id != '') AND ($first) ){
									echo "checked='checked'";
								}
								echo " type='radio' /></td>\n";
								echo " <td align='center'> ".sprintf("%02d.%02d.%04d", $day_dep, $month_dep, $year_dep)."<br />-<br />".sprintf("%02d.%02d.%04d", $day_arr, $month_arr, $year_arr)."\n";
								echo "						</td>\n";
								echo "						<td class='tb_right'>\n"; 
							
								if ( ($record->dat_id != '') AND ($first) ){
									echo "							<select size='1' id='double' name='double' onchange='loadfields(\"2\",\"".JText::_( 'TB Status ready', true )."\",\"".JText::_( 'TB One moment', true )."\")'>\n";
									echo "								<option value='0'>".JText::sprintf('TB Doppelzimmer', 0)."</option>\n";
									echo "								<option value='1' selected='selected'>".JText::sprintf('TB Doppelzimmer', 1)."</option>\n";
									echo "								<option value='2'>".JText::sprintf('TB Doppelzimmer', 2)."</option>\n";
									echo "								<option value='3'>".JText::sprintf('TB Doppelzimmer', 3)."</option>\n";
									echo "								<option value='4'>".JText::sprintf('TB Doppelzimmer', 4)."</option>\n";
									$total = $total + 2*$record->rate;
									echo "							</select>\n";
								} 
								echo "						".number_format($record->rate, 0, ',', '.')."&nbsp;".$this->currency."\n";
								echo "						</td>
								<td class='tb_right'>\n";				
								if ( ($record->dat_id != '') AND ($first) ){
									echo "							<select size='1' id='single' name='single' onchange='loadfields(\"1\",\"".JText::_( 'TB Status ready', true )."\",\"".JText::_( 'TB One moment', true )."\")'>\n";
									echo "								<option value='0' selected='selected'>".JText::sprintf('TB Einzelzimmer', 0)."</option>\n";
									echo "								<option value='1'>".JText::sprintf('TB Einzelzimmer', 1)."</option>\n";
									echo "								<option value='2'>".JText::sprintf('TB Einzelzimmer', 2)."</option>\n";
									echo "								<option value='3'>".JText::sprintf('TB Einzelzimmer', 3)."</option>\n";
									echo "								<option value='4'>".JText::sprintf('TB Einzelzimmer', 4)."</option>\n";
									echo "								<option value='5'>".JText::sprintf('TB Einzelzimmer', 5)."</option>\n";
									echo "								<option value='6'>".JText::sprintf('TB Einzelzimmer', 6)."</option>\n";
									echo "								<option value='7'>".JText::sprintf('TB Einzelzimmer', 7)."</option>\n";
									echo "								<option value='8'>".JText::sprintf('TB Einzelzimmer', 8)."</option>\n";
									echo "							</select>\n";
								}
								echo "							".number_format($record->rate+$record->single_supplement, 0, ',', '.')."&nbsp;".$this->currency."\n";
								echo "						</td>\n";
								if (($this->params->get( 'feeder' )) AND ($this->feeders)) {
									echo "<td class='tb_right'>".number_format($this->feeders[0]->markup, 0, ',', '.')."&nbsp;".$this->currency."</td>";
								}
								if (($this->params->get( 'hotel_select' )) AND ($this->tour_items[0]->select_hotel) AND ($this->hotels_items)) {
									$hotel_rate_total = $this->hotels_items[0]->hotel_rate*$this->hotels_items[0]->accomodation;
									echo "<td class='tb_right'>".number_format($hotel_rate_total, 2, ',', '.')."&nbsp;".$this->currency."</td>";
								}
								$first = false;
								echo "					</tr>\n";
						}
						echo "				</tbody>\n";
						echo "			</table>\n";
						echo "		</div>\n";
			
						echo "		<span class='tb_small tb_left'>".JText::_('TB Rates subject')."</span>\n";
			
			// Show Sub-Total
				echo "		<span id='ajax_sub_total'>total: ".number_format($total, 2, ',', '.')."&nbsp;".$this->currency."</span>\n";
				echo "	</fieldset>\n";
			
			// Show NEXT-Button
				echo "	<fieldset>\n";
				echo "		<legend>".JText::_('TB Navigation')."</legend>\n";
				echo "		<div id='ajax_next'>\n";
				echo "			<input type='button' class='button button_right' value='".JText::_('TB next')."' onclick='loadfields(\"1\",\"".JText::_( 'TB Status ready', true )."\",\"".JText::_( 'TB One moment', true )."\"); collapseElem(\"mainForm_1\"); expandElem(\"mainForm_2\"); scrollTo(0,0)' />\n";	
				echo "		</div >\n";
				echo "	</fieldset>\n";
			
				echo "</div>\n";
				?>
			<!-- FORM PAGE TWO -->
			<div id="mainForm_2">   
				<ul class="tabs-nav">
					<li class="active" onclick="collapseElem('mainForm_2'); expandElem('mainForm_1');"><a name="Termin"></a><?php echo JText::_('TB Terminauswahl');?></li>
					<li class="current"><a name="Service"></a><?php echo JText::_('TB Leistungen');?></li>
					<li class="inactive"><a name="Paxe"></a><?php echo JText::_('TB Reiseteilnehmer');?></li>
					<li class="inactive"><a name="Kontakt"></a><?php echo JText::_('TB Kontaktdaten');?></li>
					<li class="inactive"><a name="Summary"></a><?php echo JText::_('TB Summary Application');?></li>
				</ul>
				<br />
				<?php 
					if ($this->params->get( 'contact' ) && $this->params->get( 'telefon' )) {
						echo "<strong>\n"; 
						echo JText::_('TB Sie haben Fragen zur Buchung')."<br />".JText::sprintf('TB Wir stehen Ihnen',$this->params->get( 'telefon' ));
						echo "</strong>\n";
					}
				?>
			<!-- PAGE TWO -->
				<div id="ajax_page2"></div>
			</div>
			<!-- FORM PAGE THREE -->
			<div id="mainForm_3">   
				<ul class="tabs-nav">
					<li class="active" onclick="collapseElem('mainForm_3'); expandElem('mainForm_1');"><a name="Termin"></a><?php echo JText::_('TB Terminauswahl');?></li>
					<li class="active" onclick="collapseElem('mainForm_3'); expandElem('mainForm_2');"><a name="Service"></a><?php echo JText::_('TB Leistungen');?></li>
					<li class="current"><a name="Paxe"></a><?php echo JText::_('TB Reiseteilnehmer');?></li>
					<li class="inactive"><a name="Kontakt"></a><?php echo JText::_('TB Kontaktdaten');?></li>
					<li class="inactive"><a name="Summary"></a><?php echo JText::_('TB Summary Application');?></li>
				</ul>
				<br />
				<?php 
					if ($this->params->get( 'contact' ) && $this->params->get( 'telefon' )) {
						echo "<strong>\n"; 
						echo JText::_('TB Sie haben Fragen zur Buchung')."<br />".JText::sprintf('TB Wir stehen Ihnen',$this->params->get( 'telefon' ));
						echo "</strong>\n";
					}
				?>
			<!-- PAGE THREE -->
				<div id="ajax_page3"></div>
			</div>
			<!-- FORM PAGE FOUR -->
			<div id="mainForm_4">   
				<ul class="tabs-nav">
					<li class="active" onclick="collapseElem('mainForm_4'); expandElem('mainForm_1');"><a name="Termin"></a><?php echo JText::_('TB Terminauswahl');?></li>
					<li class="active" onclick="collapseElem('mainForm_4'); expandElem('mainForm_2');"><a name="Service"></a><?php echo JText::_('TB Leistungen');?></li>
					<li class="active" onclick="collapseElem('mainForm_4'); expandElem('mainForm_3');"><a name="Paxe"></a><?php echo JText::_('TB Reiseteilnehmer');?></li>
					<li class="current"><a name="Kontakt"></a><?php echo JText::_('TB Kontaktdaten');?></li>
					<li class="inactive"><a name="Summary"></a><?php echo JText::_('TB Summary Application');?></li>
				</ul>
				<br />
				<?php 
					if ($this->params->get( 'contact' ) && $this->params->get( 'telefon' )) {
						echo "<strong>\n"; 
						echo JText::_('TB Sie haben Fragen zur Buchung')."<br />".JText::sprintf('TB Wir stehen Ihnen',$this->params->get( 'telefon' ));
						echo "</strong>\n";
					}
				?>
			<!-- PAGE FOUR -->
				<div id="ajax_page4"></div>
			</div>
			<!-- FORM PAGE FIVE -->
			<div id="mainForm_5">
				<ul class="tabs-nav">
					<li class="active" onclick="collapseElem('mainForm_5'); expandElem('mainForm_1');"><a name="Termin"></a><?php echo JText::_('TB Terminauswahl');?></li>
					<li class="active" onclick="collapseElem('mainForm_5'); expandElem('mainForm_2');"><a name="Service"></a><?php echo JText::_('TB Leistungen');?></li>
					<li class="active" onclick="collapseElem('mainForm_5'); expandElem('mainForm_3');"><a name="Paxe"></a><?php echo JText::_('TB Reiseteilnehmer');?></li>
					<li class="active" onclick="collapseElem('mainForm_5'); expandElem('mainForm_4');"><a name="Kontakt"></a><?php echo JText::_('TB Kontaktdaten');?></li>
					<li class="current"><a name="Summary"></a><?php echo JText::_('TB Summary Application');?></li>
				</ul>
				<br />
				<?php 
					if ($this->params->get( 'contact' ) && $this->params->get( 'telefon' )) {
						echo "<strong>\n"; 
						echo JText::_('TB Sie haben Fragen zur Buchung')."<br />".JText::sprintf('TB Wir stehen Ihnen',$this->params->get( 'telefon' ));
						echo "</strong>\n";
					}
				?>
			<!-- PAGE FIVE -->
				<div id="booking_email">
					<div id="ajax_page5"></div>	
				</div>
			</div>
			<!-- STATUS -->
			<span id='ajax_status'><?php echo JText::_('TB Fertig');?></span><span id='validate' style='display: none;'><?php echo JText::_('TB RED');?></span><span id='validate5' style='display: none;'><?php echo JText::_('TB FEHLER');?></span>
			<!-- Copyright -->
			<?php 
				$cright = 	"<span class='copyright' id='tb_footer'>\n".
							"	<strong>TOURbooks</strong> for JOOMLA!&trade; by <a href='http://www.demo-page.de'>demo-page.de</a>\n".
							"</span>";
				echo $cright;
			?>
		</div>
	</div>

<?php 
// pass redirect
	if ($this->params->get( 'redirect' )) {
		echo "<input type='hidden' name='redirect' value='".$this->params->get( 'redirect' )."' />";
	} else {
		echo "<input type='hidden' name='redirect' value='".JURI::base()."' />";
	}
?>
	
	<input type="hidden" name="option" value="com_travelbook" />
	<input type="hidden" name="controller" value="travelbook" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
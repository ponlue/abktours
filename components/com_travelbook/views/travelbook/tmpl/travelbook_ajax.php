<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: travelbook_ajax.php 2 2010-04-13 13:37:46Z WEB $
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

// Get the user's Selection
$tour	= JRequest::getVar('tour', '', 'post', 'string', JREQUEST_ALLOWRAW);
$pax	= JRequest::getVar('pax', '', 'post', 'string', JREQUEST_ALLOWRAW);
$single	= JRequest::getVar('single', '', 'post', 'string', JREQUEST_ALLOWRAW);
$double	= JRequest::getVar('double', '', 'post', 'string', JREQUEST_ALLOWRAW);
$season	= JRequest::getVar('season', '', 'post', 'string', JREQUEST_ALLOWRAW);
$feeder	= JRequest::getVar('feeder', '', 'post', 'string', JREQUEST_ALLOWRAW);
$hotel	= JRequest::getVar('hotel', '', 'post', 'string', JREQUEST_ALLOWRAW);
$status	= JRequest::getVar('status', '', 'post', 'string', JREQUEST_ALLOWRAW);
// 99 = Tour has been changed
// 88 = season has been changed (feeder is depending)
// 2 double has been chagend
// 1 single has been changed
// 0 = Paxe, Feeder, Hotel has been changed

// Hidden input for email und database
// $hidden_tour ='';
// $hidden_abflug ='';
// $hidden_pax ='';
// $hidden_departure ='';
// $hidden_arrival ='';
// $hidden_einzel ='';
// $hidden_doppel ='';

// Initialize Rates
$rate_total = 0;
$rate_single = 0;
$rate_double = 0;
$tour_date = '';

// Tour has been selected?
if ($status != 99) {
	$season_sel = $season;
} else {
	$season_sel = $this->tour_items[0]->dat_id;
}
// Begin Layout
echo "*#*"."yes"."#999-#";
// field for tb_clt_dat
echo "<input type='hidden' name='tbtour[dat_id]' value='".$season_sel."'>\n";
// Show Image
if ($this->params->get( 'image' )) {
	echo "	<img src='".$this->tour_items[0]->picture."' alt='".$this->tour_items[0]->title."' />\n";
}
//echo "<img src='".$this->tour_items[0]->picture."' alt='".$this->tour_items[0]->title."'>";
echo "#999-#";

// Show Title
if ($this->params->get( 'title' )) {
	echo $this->tour_items[0]->title."\n";
}
//echo $this->tour_items[0]->title;
echo "#999-#";
// Show Subtitle
echo "<h2>".JText::_('TB CHOOSE YOUR TRIP')."</h2>";

echo "#999-#";
// Hotel
if (($this->params->get( 'hotel_select' )) AND ($this->tour_items[0]->select_hotel) AND ($this->hotels_items)) {
// Show Hotel Selector
	echo "		<div id='ajax_accomodation'>\n";
	echo "			<label for='accomodation' class='required extra' accesskey='h'>".JText::_('TB Accomodation').": </label>\n";
	echo "			<select class='page1' name='accomodation' size='1' id='accomodation' onChange='loadfields(\"86\",\"".JText::_( 'TB Status ready', true )."\",\"".JText::_( 'TB One moment', true )."\")'>\n";
	foreach ( $this->hotels_items as $key => $record ) {
		if ($record->id == $hotel) {
			$hotel_key = $key;
			echo "        <option value='".$record->id."' selected='selected'>".$record->name." ".$record->category." ".$record->chain."</option>"; 
		} else {
			echo "        <option value='".$record->id."'>".$record->name." ".$record->category." ".$record->chain."</option>"; 
		}
	}
	echo "			</select>\n";
	echo "		</div>\n";
} else {
	echo "		<div id='ajax_accomodation'>\n";
	echo "		<div id='accomodation' value='0'></div>\n";
	echo "		</div>\n";
}

echo "#999-#";
// Feeder
if (($this->params->get( 'feeder' )) AND ($this->feeders)) {
// Show Feeder Selector
	echo "			<label for='feeder' class='required extra' accesskey='a'>".JText::_('TB Feeder').": </label>\n";
	echo "			<select class='page1' name='feeder' size='1' id='feeder' onChange='loadfields(\"87\",\"".JText::_( 'TB Status ready', true )."\",\"".JText::_( 'TB One moment', true )."\")'>\n";
	foreach ( $this->feeders as $key => $record ) {
		if ($record->id == $feeder) {
			$feeder_key = $key;
			echo "        <option value='".$record->id."' selected='selected'>".$record->name."</option>"; 
			$rate_total = $rate_total + $pax*$record->markup;
		} else {
			echo "        <option value='".$record->id."'>".$record->name."</option>"; 
		}
	}
	echo "			</select>\n";
} else {
	echo "		<div id='ajax_feeder'>\n";
	echo "		<div id='feeder' value='0'></div>\n";
	echo "		</div>\n";
}

echo "#999-#";
// Show Pax Selector
echo "\n";
echo "			<label for='paxe' class='required extra' accesskey='p'>".JText::_('TB Number Traveller').":</label>\n";
echo "				<select class='page1' name='paxe' size='1' id='pax' onChange='loadfields(\"0\",\"".JText::_( 'TB Status ready', true )."\",\"".JText::_( 'TB One moment', true )."\")'>\n";
if ($pax == 1) {
	echo "					<option value='1' selected='selected'>".JText::sprintf('TB Erwachsener', 1)."</option>\n";
} else {
	echo "					<option value='1'>".JText::sprintf('TB Erwachsener', 1)."</option>\n";
}
for ($i = 2; $i <= 8; $i++) {
	if ($pax == $i) {
	echo "					<option value='".$i."' selected='selected'>".JText::sprintf('TB Erwachsene', $i)."</option>\n";
	} else {
	echo "					<option value='".$i."'>".JText::sprintf('TB Erwachsene', $i)."</option>\n";
	}
}
echo "				</select>\n		";
echo "#999-#";

// Show Seasons
echo "\n";
echo "			<table cellpadding='3' width='100%'>
				<thead class='tb'>
					<tr>
						<th style='width: 11%;' class='tb_left' colspan='2'>".JText::_('TB Date')."</th>
						<th style='width: 35%;' class='tb_right'>".JText::sprintf('TB Doppelzimmer2', '')."</th>
						<th style='width: 30%;' class='tb_right'>".JText::sprintf('TB Einzelzimmer2', '')."</th>";
				if (($this->params->get( 'feeder' )) AND ($this->feeders)) {
					echo "<th style='width: 12%;' class='tb_right'>".JText::sprintf('TB Feeder2', '')."</th>";
				}
				if (($this->params->get( 'hotel_select' )) AND ($this->tour_items[0]->select_hotel) AND ($this->hotels_items)) {
					echo "<th style='width: 12%;' class='tb_right'>".JText::sprintf('TB Hotel2', '')."</th>";
				}
echo "				</tr>
				</thead>
				<tbody>\n";

$id = '';
foreach ( $this->tour_items as $record ) {
		list($year_dep, $month_dep, $day_dep) = explode("-", $record->departure);
		list($year_arr, $month_arr, $day_arr) = explode("-", $record->arrival);
		
		echo "					<tr"; 
		if ($record->dat_id == $season_sel) {
			echo " class='season_selected' ";
		}    
		
		echo ">\n						<td class='tb_left'>
							<input type='radio' name='season' value='".$record->dat_id."' onClick='loadfields(\"88\",\"".JText::_( 'TB Status ready', true )."\",\"".JText::_( 'TB One moment', true )."\")' ";
		if ($record->dat_id == $season_sel) {
			echo "checked";
			$tour_date = sprintf("%02d.%02d.%04d", $day_dep, $month_dep, $year_dep)." - ".sprintf("%02d.%02d.%04d", $day_arr, $month_arr, $year_arr);
		}
		
		echo "/></td>\n";
		echo "<td align='center'> ".sprintf("%02d.%02d.%04d", $day_dep, $month_dep, $year_dep)."<br />-<br />".sprintf("%02d.%02d.%04d", $day_arr, $month_arr, $year_arr)."\n";
		echo "						</td>
						<td class='tb_right'>\n"; 
	
		if ($record->dat_id == $season_sel) {
			echo "							<select size='1' id='double' name='double' onChange='loadfields(\"2\",\"".JText::_( 'TB Status ready', true )."\",\"".JText::_( 'TB One moment', true )."\")'><div id='ajax_double'>\n";
// If User changes pax
			if ($status == 0) {
// One person
				if (intval($pax/2) == 0) {
					echo "								<option value='0' selected='selected'>".JText::sprintf('TB Doppelzimmer', 0)."</option>\n";
					$double=0;
// Two or more persons
				} else {
					echo "								<option value='0'>".JText::sprintf('TB Doppelzimmer', 0)."</option>\n";
				}
		
				for ($count = 1; $count <= $pax; $count++)
				{ 
					if ($count == intval($pax/2)) {
						echo "								<option value='".$count."' selected='selected'>".JText::sprintf('TB Doppelzimmer', $count)."</option>\n";
					    $double=$count;
						$rate_double = number_format($record->rate, 2, ',', '.')."&nbsp;".$this->currency;
						$rate_total = $rate_total + 2*$record->rate*$count;
					} else {
						echo "								<option value='".$count."'>".JText::sprintf('TB Doppelzimmer', $count)."</option>\n";
					}
				}
// If User changes hotel, feeder, season or tour
			} else {
				for ($count = 0; $count <= $pax; $count++)
				{ 
					if ($count == $double) {
						echo "								<option value='".$count."' selected='selected'>".JText::sprintf('TB Doppelzimmer', $count)."</option>\n";
					    $double=$count;
						$rate_double = number_format($record->rate, 2, ',', '.')."&nbsp;".$this->currency;
						$rate_total = $rate_total + 2*$record->rate*$count;
					} else {
						echo "								<option value='".$count."'>".JText::sprintf('TB Doppelzimmer', $count)."</option>\n";
					}
				}
			}
			echo "							</select>\n";
		}	
		
		echo "							".number_format($record->rate, 0, ',', '.')."&nbsp;".$this->currency."
						</td>
						<td class='tb_right'>\n";
		
		if (($record->dat_id == $season) OR ($record->dat_id == $season_sel)) {
			echo "							<select size='1' id='single' name='single' onChange='loadfields(\"1\",\"".JText::_( 'TB Status ready', true )."\",\"".JText::_( 'TB One moment', true )."\")'><div id='ajax_single'>\n";
// If User changes pax
			if ($status == 0) {
// One person
				if (($pax % 2) == 0) {
					echo "								<option value='0' selected='selected'>".JText::sprintf('TB Einzelzimmer', 0)."</option>\n";
					$single=0;
// Two or more persons
				} else {
					echo "								<option value='0'>".JText::sprintf('TB Einzelzimmer', 0)."</option>\n";
				}
	
				for ($count = 1; $count <= $pax; $count++) { 
					if ($count == ($pax % 2)) {
					  echo "								<option value='".$count."' selected='selected'>".JText::sprintf('TB Einzelzimmer', $count)."</option>\n";
					  $single=$count;
					  $rate_single = number_format($record->rate+$record->single_supplement, 2, ',', '.')."&nbsp;".$this->currency;
					  $rate_total = $rate_total + ($record->rate+$record->single_supplement)*$count;
					}
					else {
					  echo "								<option value='".$count."'>".JText::sprintf('TB Einzelzimmer', $count)."</option>\n";
	
					}
				}
// If User changes feeder, hotel, season or tour
			} else {
				for ($count = 0; $count <= $pax; $count++) { 
					if ($count == $single) {
					  echo "								<option value='".$count."' selected='selected'>".JText::sprintf('TB Einzelzimmer', $count)."</option>\n";
					  $single=$count;
					  $rate_single = number_format($record->rate+$record->single_supplement, 2, ',', '.')."&nbsp;".$this->currency;
					  $rate_total = $rate_total + ($record->rate+$record->single_supplement)*$count;
					}
					else {
					  echo "								<option value='".$count."'>".JText::sprintf('TB Einzelzimmer', $count)."</option>\n";
					}
				}
			}
	
			echo "							</select>";
		}
		echo "							".number_format($record->rate+$record->single_supplement, 0, ',', '.')."&nbsp;".$this->currency."
						</td>";


								if (($this->params->get( 'feeder' )) AND ($this->feeders)) {
									echo "<td class='tb_right'>".number_format($this->feeders[$feeder_key]->markup, 0, ',', '.')."&nbsp;".$this->currency."</td>";
								}
								if (($this->params->get( 'hotel_select' )) AND ($this->tour_items[0]->select_hotel) AND ($this->hotels_items)) {
									$hotel_rate_total = $this->hotels_items[$hotel_key]->hotel_rate*$this->hotels_items[$hotel_key]->accomodation;
									echo "<td class='tb_right'>".number_format(($hotel_rate_total), 2, ',', '.')."&nbsp;".$this->currency."</td>";
								}


		echo "			</tr>\n";
}
echo "				</tbody>\n";
echo "			</table>\n		";
echo "#999-#";


// Display Sub-Total
// If User changes double or single, but NOT pax
$rate_total = $rate_total + ($single + $double)*$hotel_rate_total;
if ($status != 0) {
	if ($single + 2*$double != $pax) {
		echo "\n";
		echo "#999-#";
		echo "\n			<dl id='system-message'>
				<dt class='error'>".JText::_('Error')."</dt>
				<dd class='error message fade'>
					<ul>
						<li>".JText::_('TB SINGLE DOUBLE')."</li>
					</ul>
				</dd>
			</dl>\n		";
		echo "#999-#";
		echo "";
	} else {
		echo "total: ".number_format($rate_total, 2, ',', '.')."&nbsp;".$this->currency;
		echo "#999-#";
		echo "\n			<input type='button' class='button button_right' value='".JText::_('TB next')."' onclick='loadfields(\"1\",\"".JText::_( 'TB Status ready', true )."\",\"".JText::_( 'TB One moment', true )."\",\"".JText::_( 'TB One moment', true )."\"); collapseElem(\"mainForm_1\"); expandElem(\"mainForm_2\"); scrollTo(0,0)'></span>\n		";	
	}  

// If User changes pax
} else {
	echo "total: ".number_format($rate_total, 2, ',', '.')."&nbsp;".$this->currency;	
	echo "#999-#";
	echo "\n			<input type='button' class='button button_right' value='".JText::_('TB next')."' onclick='collapseElem(\"mainForm_1\"); expandElem(\"mainForm_2\"); scrollTo(0,0)'></span>\n		";	
}
echo "#999-#";

// PAGE 2
echo "		<h2>".JText::_('TB SERVICES')."</h2>\n";
echo "		<div class='subcolumns'>";
// Services
if ( count($this->services_items)>0) {
	echo "<div id='box_services_links'>\n";
// all inclusive
	echo "	<fieldset>\n";
	echo "		<legend>".JText::_('TB All Inclusiv')."</legend>\n";
	echo "		<div class='tb_services_start'>".$this->services_items[0]->title."</div>\n"; 
		$typ_id = $this->services_items[0]->catid;
		$index = 1;
		foreach ($this->services_items as $record) {
//			if ( $typ_id != $record->typ_id ) {
			if ( $typ_id != $record->catid ) {
				echo "		<div class='tb_services'>".$record->title."</div>\n";
			} 
			echo "			(".$index.") ".$record->name."<br />\n"; 
			$typ_id = $record->catid;
			$index++;
		}
	echo "	</fieldset>\n";
//	echo "</div>\n";
	echo "</div>\n";

// additional services
	if (count($this->add_services_items)>0) {
	echo "<div id='box_services_rechts'>\n";
	echo "	<fieldset>\n";
	echo "		<legend>".JText::_('TB On Top')."</legend>\n";

//		$typ_id = $this->add_services_items[0]->srv_type;
		$typ_id = $this->add_services_items[0]->type;
//		$typ_id = NULL;
		$index = 0;
		$service_index = 0;
		echo "<div class='tb_services_start' id='tb_services0'>".$this->add_services_items[0]->type."</div>\n";
		foreach ($this->add_services_items as $record) {
			if ( $typ_id != $record->type ) {
				$service_index++;
				echo "<div class='tb_services' id='tb_services".$service_index."'>".$record->type."</div>\n";
			} 
			if ( $record->checked ) {
				$checked = "checked='checked'";
			} else {
				$checked = '';
			}
			echo "		<input type='".$record->category."' id='cb".$service_index.$index."' name='".$record->category.$service_index."' value='".$record->name."' ".$checked."> ".$record->name." (".number_format($record->rate, 0, ',', '.')."&nbsp;".$this->currency.")<br />\n";
			echo "		<input type='hidden' name='value_".$record->category.$service_index."' value='".$record->pro_rata." ".number_format($record->rate, 0, ',', '.')."&nbsp;".$this->currency."'>\n";
			echo "		<input type='hidden' name='wert_".$record->category.$service_index."' value='".$pax*$record->rate."'>\n";
			$typ_id = $record->type;
			$index++;
		}
	}
	echo "	</fieldset>\n";
//	echo "</div>\n";
	echo "</div>\n";
	echo "</div>\n";
}
	
// Display Hotels
	if (($this->params->get( 'hotel_select' )) AND !($this->tour_items[0]->select_hotel) AND ($this->hotels_items)) {
		if (count($this->hotels_items)>0) {
			echo "<div id='ajax_hotels'>\n";
			echo "	<fieldset>\n";
			echo "		<legend>".JText::_('TB Hotels')."</legend>\n";
			
			$index = 1;
			foreach ($this->hotels_items as $record) {
				echo "		<b>".$record->city.":</b> ".$record->accomodation; 
				if ($record->accomodation == 1) {echo JText::_('TB Night');} else {echo JText::_('TB Nights');}; 
				echo $record->category." ".$record->name."<br />\n";
				$index++;
			}
			echo "	</fieldset>\n";
			echo "</div>\n";	
		}
	}

	if (($this->params->get( 'hotel_select' )) AND ($this->tour_items[0]->select_hotel) AND ($this->hotels_items)) {
		if (count($this->hotels_items)>0) {
			echo "<div id='ajax_hotels'>\n";
			echo "	<fieldset>\n";
			echo "		<legend>".JText::_('TB Hotels')."</legend>\n";
			
			$index = 1;
			foreach ($this->hotels_items as $record) {
				if ($record->id == $hotel) {
					echo "		<b>".$record->city.":</b> ".$record->accomodation; 
					if ($record->accomodation == 1) {echo JText::_('TB Night');} else {echo JText::_('TB Nights');}; 
					echo $record->category." ".$record->name."<br />\n";
				}
				$index++;
			}
			echo "	</fieldset>\n";
			echo "</div>\n";	
		}
	}
	
// Show NEXT-Button
	echo "	<fieldset>\n";
	echo "		<legend>".JText::_('TB Navigation')."</legend>\n";
	echo "		<input type='button' class='button button_left'  value='".JText::_('TB previous')."' onclick='collapseElem(\"mainForm_2\"); expandElem(\"mainForm_1\");'>\n";	
	echo "		<input type='button' class='button button_right' value='".JText::_('TB next')."'  onclick='collapseElem(\"mainForm_2\"); expandElem(\"mainForm_3\"); scrollTo(0,0);  liveValidation3(".$pax.");'>\n";	
	echo "	</fieldset>\n";
echo "#999-#";

// PAGE 3
echo "<h2>".JText::_('TB All Partipiciants')."</h2>\n";
for($count = 1; $count <= $pax; $count++) {
	if ($odd = $count%2) {
		echo "<div id='box_guests_links'>\n";
	} else {	
		echo "<div id='box_guests_rechts'>\n";
	}
	echo "	<fieldset>
		<legend>".JText::sprintf('TB Partipiciant', $count)."</legend>
		<label for='anrede_".$count."' class='required extra'>".JText::_('TB Title').": </label>
		<select id='anrede_".$count."' name='tbguests[".$count."][title]' size='1' tabindex='".$count."' value='' title='".JText::sprintf('TB Title Title', $count)."'>
			<option selected='selected'>".JText::_('TB MR')."</option>
			<option>".JText::_('TB MS')."</option>
		</select><br />
		<label for='firstname_".$count."' class='required extra'>".JText::_('TB First Name').": </label>
		<input type='text' class='required LV_invalid_field' id='firstname_".$count."' name='tbguests[".$count."][first_name]' tabindex='".$count."' value='' title='".JText::sprintf('TB Title First Name', $count)."'><br />
		<label for='lastname_".$count."' class='required extra'>".JText::_('TB Last Name').": </label>
		<input type='text' class='required LV_invalid_field' id='lastname_".$count."' name='tbguests[".$count."][last_name]' tabindex='".$count."' value='' title='".JText::sprintf('TB Title Last Name', $count)."'><br />
		<label for='birthday_".$count."' class='required extra'>".JText::_('TB Birthdate').": </label>
		<input type='text' class='required LV_invalid_field datum' id='birthday_".$count."' name='tbguests[".$count."][birthdate]' tabindex='".$count."' value='' title='".JText::sprintf('TB Title Birthdate', $count)."'><br />
	</fieldset>
</div>";
}

// Show NEXT-Button
	echo "	<fieldset>\n";
	echo "		<legend>".JText::_('TB Navigation')."</legend>\n";
	echo "		<input type='button' class='button button_left'  tabindex='9' value='".JText::_('TB previous')."' onclick='collapseElem(\"mainForm_3\"); expandElem(\"mainForm_2\"); liveValidation2();'>\n";	
	echo "		<input type='button' class='button button_right' tabindex='9' value='".JText::_('TB next')."'     onclick='collapseElem(\"mainForm_3\"); expandElem(\"mainForm_4\"); scrollTo(0,0); replace_contact(); liveValidation4();'>\n";	
	echo "	</fieldset>\n";
echo "#999-#";

// PAGE 4
echo "<h2>".JText::_('TB Contact')."</h2>";
echo "<div id='box_contact_links'>\n";
echo "	<fieldset>\n";
echo "		<legend>Kontaktdaten</legend>
			<label for='anrede_88' class='required extra'>".JText::_('TB Title').": </label>
			<select id='anrede_88' name='tbclient[title]' size='1' tabindex='88' value='' title='".JText::_('TB Title Contact Title')."'>
				<option>".JText::_('TB MR')."</option>
			  	<option selected='selected'>".JText::_('TB MS')."</option>
			</select><br />
			<label for='firstname_88' class='required extra'>".JText::_('TB First Name').": </label>
			<input type='text' class='required LV_invalid_field' id='firstname_88' name='tbclient[first_name]' tabindex='89' value='' title='".JText::_('TB Title Contact First Name')."'><br />
			<label for='lastname_88' class='required extra'>".JText::_('TB Last Name').": </label>
			<input type='text' class='required LV_invalid_field' id='lastname_88' name='tbclient[last_name]' tabindex='90' value='' title='".JText::_('TB Title Contact Last Name')."'><br />
			<label for='birthday_88' class='required extra'>".JText::_('TB Birthdate').": </label>
			<input type='text' class='required LV_invalid_field datum' id='birthday_88' name='tbclient[birthdate]' tabindex='91' value='' title='".JText::_('TB Title Contact Birthdate')."'><br />
			<label for='strasse' class='required extra'>".JText::_('TB Street').": </label>
			<input type='text' class='required LV_invalid_field street' id='strasse' name='tbclient[street]' tabindex='92' value='' title='".JText::_('TB Title Contact Street')."'><br />
			<label for='plz' class='required extra'>".JText::_('TB City').": </label>
			<input type='text' class='required LV_invalid_field cip' id='plz' name='tbclient[cip]' tabindex='94' size='3' maxlength='5' value='' title='".JText::_('TB Title Contact CIP')."'>
			<input type='text' class='required LV_invalid_field city' id='ort' name='tbclient[city]' tabindex='95' value='' title='".JText::_('TB Title Contact City')."'><br />
			<label for='land' class='required extra'>".JText::_('TB Country').": </label>
			<select id='land' name='tbclient[country]' size='1' tabindex='96' value='' title='".JText::_('TB Title Contact Country')."'>
				<option selected='selected'>".JText::_('TB Germany')."</option>
				<option>".JText::_('TB Austria')."</option>
				<option>".JText::_('TB Swizerland')."</option>
				<option>".JText::_('TB Netherlands')."</option>
			</select><br />
			<label for='telefon' class='required extra'>".JText::_('TB Fon').": </label>
			<input type='text' class='required LV_invalid_field' id='telefon' name='tbclient[telefone]' tabindex='97' value='' title='".JText::_('TB Title Contact Fon')."'><br />
			<label for='email' class='required extra'>".JText::_('TB Mail').": </label>
			<input type='text' class='required LV_invalid_field' id='email' name='tbclient[email]' tabindex='98' value='' title='".JText::_('TB Title Contact Mail')."'><br />
		</fieldset>
	</div>	
	<div id='box_contact_rechts'>			
		<fieldset>
			<legend>".JText::_('TB Comments')."</legend>
			<label for='kommentar' class='comment'>".JText::_('TB QUESTIONS')."</label>
			<textarea id='kommentar' name='tbclient[remark]' cols='40' rows='9' tabindex='99' value='' title='".JText::_('TB Title Contact Comment')."'></textarea>
		</fieldset>
	</div>";

// Show NEXT-Button
	echo "	<fieldset>\n";
	echo "		<legend>".JText::_('TB Navigation')."</legend>\n";
	echo "		<input type='button' class='button button_left'  tabindex='9' value='".JText::_('TB previous')."' onclick='collapseElem(\"mainForm_4\"); expandElem(\"mainForm_3\"); liveValidation3();'>\n";	
	echo "		<input type='button' class='button button_right' tabindex='9' value='".JText::_('TB next')."'     onclick='collapseElem(\"mainForm_4\"); expandElem(\"mainForm_5\"); liveValidation5(); scrollTo(0,0); replace_summary(".$pax.",\"".$tour_date."\",\"".$rate_single."\",\"".$rate_double."\",\"".$rate_total."\");'>\n";

	echo "	</fieldset>\n";
echo "#999-#";

// PAGE 5
echo "<h2>".JText::_('TB Check')."</h2>";

	echo "	<fieldset><legend>".JText::_('TB Summary')."</legend>
				<fieldset><legend>".JText::_('TB Tour')."</legend>

					<span class='summary_lbl'>".JText::_('TB Destination').": </span>
					<span class='summary_mid' id='summary_reise'>".$this->tour_items[0]->title."</span><br />
					<input type='hidden' name='email_reise' value='".$tour."'>
				
					<span class='summary_lbl'>".JText::_('TB DATE').": </span>
					<span class='summary_mid' id='summary_termin'>".$tour_date."</span><br />
					<input type='hidden' name='email_reisezeit' value='".$season."'>

					<span class='summary_lbl'>".JText::_('TB Number TRAVELLER').": </span>
					<span class='summary_mid' id='summary_pax'>".$pax."</span><br /><br />
					<input type='hidden' name='email_paxe' value='".$pax."'>";

// Feeder
	if (($this->params->get( 'feeder' )) AND ($this->feeders)) {
		echo "			<span class='summary_lbl'>".JText::_('TB FEEDER').": </span>";
		echo "
					<span class='summary_mid' id='summary_feeder'>".$this->feeders[$feeder_key]->name."</span>
					<span class='summary betrag_rechts'>".JText::_('TB per person')."&nbsp;<span id='summary_single_rate'>".number_format($this->feeders[$feeder_key]->markup, 2, ',', '.')."&nbsp;".$this->currency."</span>
					</span><br /><br />
					<input type='hidden' name='email_feeder' value='".JText::sprintf($this->feeders[$feeder_key]->markup, $single)."'>
					<input type='hidden' name='tbclient[feeder]' value='".$this->feeders[$hotel_key]->id."'>";
	} else {
		echo "<input type='hidden' name='email_feeder' value='0'>";
		echo "<input type='hidden' name='tbclient[feeder]' value='0'>";
	}

// Occupancy
	echo "			<span class='summary_lbl'>".JText::_('TB OCCUPANCY').": </span>";
	if ($single>0) {
		echo "
					<span class='summary_mid' id='summary_single'>".JText::sprintf('TB EINZELZIMMER', $single)."</span>
					<span class='summary betrag_rechts'>".JText::_('TB per person')."&nbsp;<span id='summary_single_rate'>".$rate_single."</span>
					</span><br />
					<input type='hidden' name='email_einzel' value='".JText::sprintf($this->currency, $single)."'>
					<input type='hidden' name='tbclient[single]' value='".$single."'>";
	} else {
		echo "<input type='hidden' name='email_einzel' value='0'>";
		echo "<input type='hidden' name='tbclient[single]' value='0'>";
	}
			
	if ($double>0) {
		echo "		
					<span class='summary_mid' id='summary_double'>".JText::sprintf('TB DOPPELZIMMER', $double)."</span>
					<span class='summary betrag_rechts'>".JText::_('TB per person')."&nbsp;<span id='summary_double_rate'>".$rate_double."</span>
					</span><br /><br />
					<input type='hidden' name='email_doppel' value='".JText::sprintf($this->currency, $double)."'>
					<input type='hidden' name='tbclient[double]' value='".$double."'>";
	} else {
		echo "<input type='hidden' name='email_doppel' value='0'>";
		echo "<input type='hidden' name='tbclient[double]' value='0'>";
	}

// Hotel
	if (($this->params->get( 'hotel_select' )) AND ($this->tour_items[0]->select_hotel) AND ($this->hotels_items)) {
		echo "			<span class='summary_lbl'>".JText::_('TB HOTEL').": </span>";
		echo "
					<span class='summary_mid' id='summary_hotel'>".$this->hotels_items[$hotel_key]->name." ".$this->hotels_items[$hotel_key]->category." ".$this->hotels_items[$hotel_key]->chain."</span>
					<span class='summary betrag_rechts'>".JText::_('TB PER ROOM')."&nbsp;<span id='summary_single_rate'>".number_format(($this->hotels_items[$hotel_key]->hotel_rate*$this->hotels_items[$hotel_key]->accomodation), 2, ',', '.')."&nbsp;".$this->currency."</span>
					</span><br />
					<input type='hidden' name='email_hotel' value='".JText::sprintf($this->hotels_items[$hotel_key]->hotel_rate*$this->hotels_items[$hotel_key]->accomodation, $single)."'>
					<input type='hidden' name='tbclient[hotel]' value='".$this->hotels_items[$hotel_key]->htl_id."'>";
	} else {
		echo "<input type='hidden' name='email_hotel' value='0'>";
		echo "<input type='hidden' name='tbclient[hotel]' value='0'>";
	}

// Services
	if ( count($this->add_services_items)>0) {
		echo "		<div id='summary_services'>";
		echo "		<br /><span class='summary_lbl'>".JText::_('TB ADDITIONAL SERVICES').": </span>";
		$typ_id = 0;
		$index = 0;
		$index_zusatzleistung = 0;
		foreach ($this->add_services_items as $record) {
			if ( $typ_id != $record->type ) {
				$index = 0;
				$index_zusatzleistung++;
				echo "<span class='traveller' id='zusatzleistung".$index_zusatzleistung."' id='summary_double'> </span>";
			} 
			echo "<div class='summary_okay' id='summary_".$record->category.$record->type.$index."'>".$record->name."<div id='summary_".$record->category."_rate".$record->type.$index."' class='summary betrag_rechts'>".$this->currency."</div></div>";
			$typ_id = $record->type;
			$index++;
		}
		echo "		</div>";
	}
	echo "<input type='hidden' id='email_services' name='tbclient[services]' value=''>";


	for ($count = 1; $count <= $pax; $count++)
		{ 
			echo "		<br /><span class='summary_lbl'>".$count.". ".JText::_('TB Traveller').": </span>
						<span class='summary_mid'><span id='summary_reisender_anrede".$count."'>ANREDE</span>
						<span id='summary_reisender_firstname".$count."'>VORNAME</span>
						<span id='summary_reisender_lastname".$count."'>NACHNAME</span>
						(<span id='summary_reisender_birthday".$count."'>26.02.1965</span>)</span>";
		}
					
				
	echo "	<br /></fieldset><br />";

	echo "	<fieldset><legend>".JText::_('TB Invoice Address')."</legend>";
	echo "		<span class='summary_lbl'>".JText::_('TB Title').": </span>
				<span class='summary_mid' id='summary_rg_anrede'>ANREDE</span><br />";

	echo "		<span class='summary_lbl'>".JText::_('TB Name').": </span>
				<span class='summary_mid' id='summary_rg_name'>NAME</span><br />";

	echo "		<span class='summary_lbl'>".JText::_('TB Address').": </span>
				<span class='summary_mid' id='summary_rg_strasse'>STRASSE</span><br />

				<span class='summary_mid' id='summary_rg_ort'>PLZ Ort</span><br />
				<span class='summary_mid' id='summary_rg_land'>Land</span><br />";

	echo "	</fieldset><br />";
		
	echo "	<fieldset><legend>".JText::_('TB KONTAKTDATEN')."</legend>";
	echo "		<span class='summary_lbl'>".JText::_('TB Fon').": </span>
				<span class='summary_mid' id='summary_telefon'>TELEFON</span><br />";

	echo "		<span class='summary_lbl'>".JText::_('TB Mail').": </span>
				<span class='summary_mid' id='summary_email'>E-MAIL</span><br />";

	echo "	</fieldset><br />";

	if ($this->params->get( 'question' )) {
		echo "	<fieldset><legend>".JText::_('TB Question')."</legend>";
		echo "  	<label class='question' for='select_4'>".JText::_('TB Attention')."</label>
					<select id='select_4' size='1' title='".JText::_('TB Title Attention')."'  name='tbclient[question1]' tabindex='501'>
						<option value=''>".JText::_('TB Choose')."</option>
						<option value='".JText::_('TB Recommandation')."'>".JText::_('TB Recommandation')."</option>
						<option value='".JText::_('TB Google')."'>".JText::_('TB Google')."</option>
						<option value='".JText::_('TB SE')."'>".JText::_('TB SE')."</option>
						<option value='".JText::sprintf('TB SITE', JURI::getHost())."'>".JText::sprintf('TB SITE', JURI::getHost())."</option>
					</select>
					<br />
				
					<label class='question' for='text_6'>".JText::_('TB Or').":</label>
					<input class='question' title='' id='text_6' name='tbclient[question2]' type='text' tabindex='502'/>";
		echo "	</fieldset><br />";
	}
	
	echo "  <fieldset><legend>".JText::_('TB Total')."</legend>
			<span class='summary_lbl'>".JText::_('TB Rate').":</span>
			<input type='hidden' name='email_single_rate' value='".$rate_single."'>
			<input type='hidden' name='email_double_rate' value='".$rate_double."'>
			<input type='hidden' name='email_total' value='total'>

			<span class='summary betrag_rechts'>
			<span id='summary_total'>total <span>&nbsp;".$this->currency."</span></span>
			<input type='hidden' name='tbclient[total]' value='total'>
			</span><br />
		</fieldset><br />";

	$text = false;

	if ( !( is_null( $this->arb ) AND is_null( $this->policy ) ) ) {
		echo "	<fieldset><legend>".JText::_('TB ARB')."</legend>
		<p>
				".JText::_('TB Please read')."
		</p>";
	}

	if ( !( is_null( $this->arb ) ) ) {
		if ( !( is_null( $this->policy ) ) ) {
//			arb + policy
	        $tick = JText::sprintf('TB Tick TWO', $this->arb->name, JText::_('TB and'), $this->policy->name );
			echo "		<div class='summary'><input type='checkbox' onChange='enableButton(2)' name='datenschutz' id='arb' value='arb'>&nbsp;<a target='_blank' href='".$this->arb->link."'>".$this->arb->name."</a>";
			echo "		</div>";
			echo "		<div class='summary'><input type='checkbox' onChange='enableButton(2)' name='datenschutz' id='datenschutz' value='datenschutz'>&nbsp;<a target='_blank' href='".$this->policy->link."'>".$this->policy->name."</a>";
			if ( $this->policy_pdf <> -1 ) {
				echo "<a target='_blank' href='".JURI::base()."/media/travelbook/".$this->policy_pdf."'><img src='components/com_travelbook/assets/images/pdf.gif'></a>";
			}
			echo "		</div>";
		} else {
//			arb
	        $tick = JText::sprintf('TB Tick ONE', $this->arb->name );
			echo "		<div class='summary'><input type='checkbox' onChange='enableButton(1)' name='datenschutz' id='arb' value='arb'>&nbsp;<a target='_blank' href='".$this->arb->link."'>".$this->arb->name."</a>";
			echo "		</div>";
		}
		if ( $this->arb_pdf <> -1 ) {
			echo "<a target='_blank' href='".JURI::base()."/media/travelbook/".$this->arb_pdf."'><img src='components/com_travelbook/assets/images/pdf.gif'></a>";
		}
	} else {
		if ( !( is_null( $this->policy ) ) ) {
//			policy
	        $tick = JText::sprintf('TB Tick ONE', $this->policy->name );
			echo "		<div class='summary'><input type='checkbox' onChange='enableButton(1)' name='datenschutz' id='datenschutz' value='datenschutz'>&nbsp;<a target='_blank' href='".$this->policy->link."'>".$this->policy->name."</a>";
			if ( $this->policy_pdf <> -1 ) {
				echo "<a target='_blank' href='".JURI::base()."/media/travelbook/".$this->policy_pdf."'><img src='components/com_travelbook/assets/images/pdf.gif'></a>";
			}
			echo "</div>";
		} else {
//			na
			$text = true;
	        $tick = "";
		}
	}
	
// Show NEXT-Button
	echo "	<fieldset>\n";
	echo "		<legend>".JText::_('TB Navigation')."</legend>\n";
	echo "		<input type='button' class='button button_left'  value='".JText::_('TB previous')."' onclick='collapseElem(\"mainForm_5\"); expandElem(\"mainForm_4\"); liveValidation4();'>\n";	

	if ( $text ) {
		echo "				<div id='ajax_buchen'>\n";
		echo "					<input type='submit' value='".JText::_('TB apply')."' id='submit' tabindex='54' class='button button_right' onclick='liveValidation6();'>\n";
		echo "				</div>\n";
	} else {
		echo "				<div id='ajax_achtung'>\n";
		echo "					<dl id='system-message'><dt class='error'>".JText::_('TB Warning')."</dt><dd class='error message fade'><ul><li>".$tick."</li></ul></dd></dl><br />\n";
		echo "				</div>\n";
		echo "				<div id='ajax_buchen' style='display:none'>\n";
		echo "					<input type='submit' value='".JText::_('TB apply')."' id='submit' tabindex='55' class='button button_right' onclick='liveValidation6();'>\n";
		echo "				</div>\n";
	}

	echo "	</fieldset>\n";

echo "#999-#";
echo "ok";

echo "*#*";

?>
/**
 * "TRAVELbook - JOOMLA! on Tour" - http://www.demo-page.de
 *
 * @version         $Id: site controllers travelbook.php 001 2010-01-22 12:00:00$
 * @copyright       Copyright 2009-2010, Peter H&ouml;cherl
 * @license         GNU General Public License (GNU GPL) GPLv2, 
 *                  - see http://www.demo-page.de/en/license-conditions.html *
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link            http://www.demo-page.de
 * @package         TRAVELbook Component
 * @revision        $Revision: 2 $
 * @lastmodified    $Date: 2010-04-13 15:37:46 +0200 (Di, 13 Apr 2010) $
*/

// JavaScript Document
function number_format( /* in: float   */ number,
                        /* in: integer */ laenge,
                        /* in: String  */ sep,
                        /* in: String  */ th_sep ) {

  number = Math.round( number * Math.pow(10, laenge) ) / Math.pow(10, laenge);
  str_number = number+"";
  arr_int = str_number.split(".");
  if(!arr_int[0]) arr_int[0] = "0";
  if(!arr_int[1]) arr_int[1] = "";
  if(arr_int[1].length < laenge){
    nachkomma = arr_int[1];
    for(i=arr_int[1].length+1; i <= laenge; i++){  nachkomma += "0";  }
    arr_int[1] = nachkomma;
  }
  if(th_sep != "" && arr_int[0].length > 3){
    Begriff = arr_int[0];
    arr_int[0] = "";
    for(j = 3; j < Begriff.length ; j+=3){
      Extrakt = Begriff.slice(Begriff.length - j, Begriff.length - j + 3);
      arr_int[0] = th_sep + Extrakt +  arr_int[0] + "";
    }
    str_first = Begriff.substr(0, (Begriff.length % 3 == 0)?3:(Begriff.length % 3));
    arr_int[0] = str_first + arr_int[0];
  }
  return arr_int[0]+sep+arr_int[1];
}

function replace_contact()

{
	document.getElementById("anrede_88").selectedIndex = document.getElementById("anrede_1").selectedIndex;
	document.getElementById("firstname_88").value = document.getElementById("firstname_1").value;
	document.getElementById("lastname_88").value = document.getElementById("lastname_1").value;
	document.getElementById("birthday_88").value = document.getElementById("birthday_1").value;

	if (!(document.getElementById("firstname_1").value == "")) {
		document.getElementById("firstname_88").className = "required LV_valid_field";
	} else {
		document.getElementById("firstname_88").className = "required LV_invalid_field";
	}

	if (!(document.getElementById("lastname_88").value == "")) {
		document.getElementById("lastname_88").className = "required LV_valid_field";
	} else {
		document.getElementById("lastname_88").className = "required LV_invalid_field";
	}
	
	if (!(document.getElementById("birthday_88").value == "")) {
		document.getElementById("birthday_88").className = "required LV_valid_field";
	} else {
		document.getElementById("birthday_88").className = "required LV_invalid_field";
	}
}

function replace_summary(pax,termin,single_rate,double_rate,total)

{
	var zusatz_rate = 0;
	var email_services = '';
	for (var j = 0; j < 9; j++) {
		for (var i = 0; i < document.getElementsByName("checkbox"+j).length; i++) {
			var zusatz = document.getElementById("tb_services"+j).firstChild.data;
			if (document.getElementsByName("checkbox"+j)[i].checked == true) {
// Bezeichnung
				document.getElementById("summary_checkbox"+zusatz+i).replaceChild(document.createTextNode(document.getElementsByName("checkbox"+j)[i].value), document.getElementById("summary_checkbox"+zusatz+i).firstChild);
// Wert
				document.getElementById("summary_checkbox_rate"+zusatz+i).replaceChild(document.createTextNode(document.getElementsByName("value_checkbox"+j)[i].value), document.getElementById("summary_checkbox_rate"+zusatz+i).firstChild);
				zusatz_rate = zusatz_rate + parseInt(document.getElementsByName("wert_checkbox"+j)[i].value);
// for DB
				email_services = email_services+document.getElementById("email_services").value+document.getElementsByName("checkbox"+j)[i].value+' '+document.getElementsByName("value_checkbox"+j)[i].value+'<br />';
			} else {
				document.getElementById("summary_checkbox"+zusatz+i).replaceChild(document.createTextNode(""), document.getElementById("summary_checkbox"+zusatz+i).firstChild);
				document.getElementById("summary_checkbox_rate"+zusatz+i).replaceChild(document.createTextNode(""), document.getElementById("summary_checkbox_rate"+zusatz+i).firstChild);
			}
		}
		for (var i = 0; i < document.getElementsByName("radio"+j).length; i++) {
			var zusatz = document.getElementById("tb_services"+j).firstChild.data;
			if (document.getElementsByName("radio"+j)[i].checked == true) {
				document.getElementById("summary_radio"+zusatz+i).replaceChild(document.createTextNode(getRadioVal(document.getElementsByName("radio"+j))), document.getElementById("summary_radio"+zusatz+i).firstChild);
				document.getElementById("summary_radio_rate"+zusatz+i).replaceChild(document.createTextNode(document.getElementsByName("value_radio"+j)[i].value), document.getElementById("summary_radio_rate"+zusatz+i).firstChild);
				zusatz_rate = zusatz_rate + parseInt(document.getElementsByName("wert_radio"+j)[i].value);
				email_services = email_services+document.getElementById("email_services").value+document.getElementsByName("radio"+j)[i].value+' '+document.getElementsByName("value_radio"+j)[i].value+'<br />';
			} else {
				document.getElementById("summary_radio"+zusatz+i).replaceChild(document.createTextNode(""), document.getElementById("summary_radio"+zusatz+i).firstChild);
				document.getElementById("summary_radio_rate"+zusatz+i).replaceChild(document.createTextNode(""), document.getElementById("summary_radio_rate"+zusatz+i).firstChild);
			}
		}
	}
	document.getElementById("email_services").value = email_services;

for (var i = 1; i < pax+1; i++) {
  	document.getElementById("summary_reisender_anrede"+i).replaceChild(document.createTextNode(document.getElementById("anrede_"+i)[document.getElementById("anrede_"+i).selectedIndex].text), document.getElementById("summary_reisender_anrede"+i).firstChild);
  	document.getElementById("summary_reisender_firstname"+i).replaceChild(document.createTextNode(document.getElementById("firstname_"+i).value), document.getElementById("summary_reisender_firstname"+i).firstChild);
  	document.getElementById("summary_reisender_lastname"+i).replaceChild(document.createTextNode(document.getElementById("lastname_"+i).value), document.getElementById("summary_reisender_lastname"+i).firstChild);
  	document.getElementById("summary_reisender_birthday"+i).replaceChild(document.createTextNode(document.getElementById("birthday_"+i).value), document.getElementById("summary_reisender_birthday"+i).firstChild);
}
			
  document.getElementById("summary_rg_anrede").replaceChild(document.createTextNode(document.getElementById("anrede_88")[document.getElementById("anrede_88").selectedIndex].text), document.getElementById("summary_rg_anrede").firstChild);
  document.getElementById("summary_rg_name").replaceChild(document.createTextNode(document.getElementById("firstname_88").value+" "+document.getElementById("lastname_88").value), document.getElementById("summary_rg_name").firstChild);
  document.getElementById("summary_rg_strasse").replaceChild(document.createTextNode(document.getElementById("strasse").value), document.getElementById("summary_rg_strasse").firstChild);
  document.getElementById("summary_rg_ort").replaceChild(document.createTextNode(document.getElementById("plz").value+" "+document.getElementById("ort").value), document.getElementById("summary_rg_ort").firstChild);
  document.getElementById("summary_rg_land").replaceChild(document.createTextNode(document.getElementById("land")[document.getElementById("land").selectedIndex].text), document.getElementById("summary_rg_land").firstChild);
  document.getElementById("summary_telefon").replaceChild(document.createTextNode(document.getElementById("telefon").value), document.getElementById("summary_telefon").firstChild);
 
  document.getElementById("summary_email").replaceChild(document.createTextNode(document.getElementById("email").value), document.getElementById("summary_email").firstChild);

  total = parseFloat(total)+parseInt(zusatz_rate);
  document.getElementsByName("tbclient[total]")[0].value = total;
  total = number_format(total,2,',','.');
  document.getElementById("summary_total").replaceChild(document.createTextNode(total), document.getElementById("summary_total").firstChild);
  document.getElementsByName("email_total")[0].value = total;
}

// ermittle ausgew�hlten Radio-Button
function enableButton(status) {
	if (status == '2') {
		if ((document.getElementsByName("datenschutz")[0].checked) && (document.getElementsByName("datenschutz")[1].checked)) {
		  document.getElementById("ajax_buchen").style.display = '';
		  document.getElementById("ajax_achtung").style.display = 'none';
		} else {
		  document.getElementById("ajax_buchen").style.display = 'none';
		  document.getElementById("ajax_achtung").style.display = '';
		}
	} else { 
		if (document.getElementsByName("datenschutz")[0].checked) {
		  document.getElementById("ajax_buchen").style.display = '';
		  document.getElementById("ajax_achtung").style.display = 'none';
		} else {
		  document.getElementById("ajax_buchen").style.display = 'none';
		  document.getElementById("ajax_achtung").style.display = '';
		}
	}
}
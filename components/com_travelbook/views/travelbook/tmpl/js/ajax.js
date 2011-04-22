/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: ajax.js 2 2010-04-13 13:37:46Z WEB $
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

/*** Ajax ***/
function getHTTPObject() {
    var xmlhttp;

    if(window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                if (!xmlhttp) {
                    xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
                }
    }
    return xmlhttp;         
}
    
var http = getHTTPObject(); // We create the HTTP Object


function loadfields(status, ready, moment){
	var tour = document.getElementById("tour").value;
	var pax = document.getElementById("pax").value;
	var season = getRadioVal(document.getElementsByName("season"));
	var single = document.getElementById("single").value;
	var double = document.getElementById("double").value;
	var feeder = document.getElementById("feeder").value;
	var hotel = document.getElementById("accomodation").value;

	http.open("POST", "index.php?option=com_travelbook&view=travelbook&layout=travelbook_ajax&tmpl=component" , true);
	http.onreadystatechange = function ()
	{   
		if (http.readyState == 1) {
			document.getElementById('ajax_status').innerHTML = moment;
		} else if (http.readyState == 4) {
			document.getElementById('ajax_status').innerHTML = 'Fehler';
			 if (http.status==200) {
				document.getElementById('ajax_status').innerHTML = '';
				var results=http.responseText.split("*#*");
				var results=results[1].split("#999-#");
				if (results[0] == 'yes') {
					document.getElementById('ajax_img').innerHTML = results[1];
					document.getElementById('ajax_title').innerHTML = results[2];			 	 
					document.getElementById('ajax_sub_title').innerHTML = results[3];
					document.getElementById('ajax_accomodation').innerHTML = results[4];			 
					document.getElementById('ajax_feeder').innerHTML = results[5];			 
					document.getElementById('ajax_pax').innerHTML = results[6];
					document.getElementById('ajax_season').innerHTML = results[7];			 
					document.getElementById('ajax_sub_total').innerHTML = results[8];			 
					document.getElementById('ajax_next').innerHTML = results[9];			 					
					document.getElementById('ajax_page2').innerHTML = results[10];			 
					document.getElementById('ajax_page3').innerHTML = results[11];			 
					document.getElementById('ajax_page4').innerHTML = results[12];			 
					document.getElementById('ajax_page5').innerHTML = results[13];			 
					document.getElementById('ajax_status').innerHTML = ready;
				}
			}
		}
	};
	//http.send(null);      
    http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");    
    http.send("tour=" + escape(tour) + "&pax=" + escape(pax) + "&season=" + escape(season)  + "&single=" + escape(single) + "&double=" + escape(double) + "&feeder=" + escape(feeder) + "&hotel=" + escape(hotel) + "&status=" + escape(status));
}
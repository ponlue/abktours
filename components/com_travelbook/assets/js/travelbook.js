/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: travelbook.js 2 2010-04-13 13:37:46Z WEB $
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

/*** Java ***/
function getRadioVal(radioObj) {
	var value = "";

	if (!radioObj.length) {
		if (radioObj.checked) value = radioObj.value;
	} else {
    	for (var i = 0; i < radioObj.length; i++) {
        	if (radioObj[i].checked) {
            	value = radioObj[i].value;
	            break;
	        }
	    }
	}
	return value;
}

function val1(obj1,obj2)
{
//	if(!valid.validate()) {
//		return false;
// 	} else {
		collapseElem(obj1);
		expandElem(obj2);
//	}
}

// collapse individual elements
function collapseElem(obj)
 {
	 var el = document.getElementById(obj);
	 el.style.display = 'none';
 }

// expand next element
function changeStatusElem(obj)
{
	var el = document.getElementById(obj);

	if (el.style.display == '') {
		el.style.display = 'none';
	} else {
		el.style.display = '';
	}
}

// expand next element
function expandElem(obj)
 {
	 var el = document.getElementById(obj);
	 el.style.display = '';
 }

// collapse all elements, except the first one
function collapseAll()
	 {
// change the following line to reflect the number of pages
		var numMainForm = 5;

		currPageId = ('mainForm_1');
		expandElem(currPageId);
		 
		for(i=2; i <= numMainForm; i++)
		{
			currPageId = ('mainForm_'+i);
			collapseElem(currPageId);
		}
	 }

// liveValidation of Page 2
function liveValidation2()
	 {
		document.getElementById("validate").style.display = 'none';
	 }
// liveValidation of Page 3
function liveValidation3(pax)
	 {

		document.getElementById("validate").style.display = '';

		for (var i = 1; i < pax+1; i++) {
			var tbFirstName = new LiveValidation('firstname_'+i, {wait: 500});
			tbFirstName.add(Validate.Presence);
			tbFirstName.add( Validate.Length, { minimum: 3} );
			var tbLastName = new LiveValidation('lastname_'+i, {wait: 500});
			tbLastName.add(Validate.Presence);
			tbLastName.add( Validate.Length, { minimum: 3} );
			var tbBirthdate = new LiveValidation('birthday_'+i, {wait: 500});
			tbBirthdate.add(Validate.Presence);
			tbBirthdate.add( Validate.Length, { minimum: 8} );
		}

	 }
// liveValidation of Page 4
function liveValidation4()
	 {
		document.getElementById("validate").style.display = '';

		var tbFirstName = new LiveValidation('firstname_88', {wait: 500});
		tbFirstName.add(Validate.Presence);
		tbFirstName.add( Validate.Length, { minimum: 3} );
		var tbLastName = new LiveValidation('lastname_88', {wait: 500});
		tbLastName.add(Validate.Presence);
		tbLastName.add( Validate.Length, { minimum: 3} );
		var tbBirthday = new LiveValidation('birthday_88', {wait: 500});
		tbBirthday.add(Validate.Presence);
		tbBirthday.add( Validate.Length, { minimum: 8} );
		var tbStrasse = new LiveValidation('strasse', {wait: 500});
		tbStrasse.add(Validate.Presence);
		tbStrasse.add( Validate.Length, { minimum: 3} );
		var tbPlz = new LiveValidation('plz', {wait: 500});
		tbPlz.add(Validate.Presence);
		tbPlz.add( Validate.Length, { minimum: 4, maximum: 8 } );
		var tbOrt = new LiveValidation('ort', {wait: 500});
		tbOrt.add(Validate.Presence);
		tbOrt.add( Validate.Length, { minimum: 3} );
		var tbTelefon = new LiveValidation('telefon', {wait: 500});
		tbTelefon.add(Validate.Presence);
		tbTelefon.add( Validate.Length, { minimum: 8} );
		var tbMail = new LiveValidation('email', {wait: 500});
		tbMail.add(Validate.Email);

		document.getElementById("validate5").style.display = 'none';

	}
// liveValidation of Page 5
function liveValidation5()
	 {
		document.getElementById("validate").style.display = 'none';
		document.getElementById("validate5").style.display = 'none';
	 }
// liveValidation of Page 5
function liveValidation6()
	 {
		document.getElementById("validate5").style.display = '';
	 }

// run the collapseAll function on page load
window.onload = collapseAll;
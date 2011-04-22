<?php

/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component ContactMap Component
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

class JElementContactMapMap extends JElement
{

	var	$_name = 'ContactMapMap';

	function fetchTooltip($label, $description, &$node, $control_name, $name) {
		return '<label id="'.$control_name.'-lbl" for="'.$control_name.'" class="hasTip" title="'.JText::_($label).'::'.JText::_($description).'">'.JText::_($label).'</label>';
	}

	function fetchElement($name, $value, &$node, $control_name)
	{
        global $mainframe;

        $lang = JFactory::getLanguage(); 
        $tag_lang=(substr($lang->getTag(),0,2)); 
		
        $mainframe->addCustomHeadTag( '<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />'); 
        $mainframe->addCustomHeadTag( '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true&language='.$tag_lang.'"></script>'); 

return '
		<script language="javascript" type="text/javascript">
			var map;
			var marker1;
		
			function init() {
				var lat, lng, zoom_carte;
				lat = document.adminForm.paramscontactmap_centre_lat.value;
				lng = document.adminForm.paramscontactmap_centre_lng.value;
				zoom_carte = parseInt(document.adminForm.paramscontactmap_zoom.value);
		
				var latlng = new google.maps.LatLng(lat, lng);
				var myOptions = {
				  zoom: zoom_carte,
				  center: latlng,
				  mapTypeId: google.maps.MapTypeId.ROADMAP
				};
		
				map = new google.maps.Map(document.getElementById("map"), myOptions);
		
			  google.maps.event.addListener(map, "bounds_changed", function() {
				   document.adminForm.paramscontactmap_zoom.value = map.getZoom();
			  });
			  // Create a draggable marker which will later on be binded to a
			  marker1 = new google.maps.Marker({
				  map: map,
				  position: new google.maps.LatLng(lat, lng),
				  draggable: true,
				  title: "Drag me!"
			  });
			  google.maps.event.addListener(marker1, "drag", function() {
				document.adminForm.paramscontactmap_centre_lat.value = marker1.getPosition().lat();
				document.adminForm.paramscontactmap_centre_lng.value = marker1.getPosition().lng();
			  });
			}
		
			// Register an event listener to fire when the page finishes loading.
			google.maps.event.addDomListener(window, "load", init);
		 
		
		</script>
        <div id="map" style="width: 100%; height: 300px; overflow:hidden;"></div>
';

	}
}

?>
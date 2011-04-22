    /*
    * ContactMap Component Google Map for Joomla! 1.5.x
    * Version 3.9
    * Creation date: Octobre 2010
    * Author: Fabrice4821 - www.gmapfp.org
    * Author email: webmaster@gmapfp.org
    * License GNU/GPL
    */
var nbre_photos_panoramino = 20;
var bounds_ContactMap = new google.maps.LatLngBounds();
var marker_map = new Array();
var marker_pano_open = new Array();
var marker_pano;
var markers_pano = {};
var index_pano = 0;
var activated_panoramino = false;
var delete_old_pano = true;

/*************************************************************/
/* Charge les photos Paroramio contenu dans la carte visible */
/* voir http://www.panoramio.com/api/                        */
/*************************************************************/
function loadPhotosPanoramino() {
	var url = 'http://www.panoramio.com/map/get_panoramas.php?order=popularity&set=public&from=0&to='+nbre_photos_panoramino+'&size=mini_square&callback=addPhotosPanoramino';
	var bounds = carteContactMap.getBounds();
	url += '&minx=' + bounds.getSouthWest().lng().toFixed(6) + '&miny=' + bounds.getSouthWest().lat().toFixed(6);
	url += '&maxx=' + bounds.getNorthEast().lng().toFixed(6) + '&maxy=' + bounds.getNorthEast().lat().toFixed(6);
	url += '&ts=' + new Date().getTime(); // prevent caching

	// use JSONP to retrieve photo data and trigger a callback to addPhotos()
    var script = document.createElement("script");
    script.setAttribute("src", url);
    script.setAttribute("type", "text/javascript");                
    document.body.appendChild(script);
}

// Ajoute les marqueurs Panoramino
function addPhotosPanoramino(data) {
	var new_markers_pano = {};
	function attachinfowindowPanoramino(marker,photo){
		var infowindow = new google.maps.InfoWindow({
			content: '<a href="'+photo.photo_url+'" target="_blank"><img src="http://mw2.google.com/mw-panoramio/photos/small/'+photo.photo_id+'.jpg" ></a><div class="gmapfp_titre_panoramino">'+photo.photo_title+'</div>'
		});
		google.maps.event.addListener(marker, 'click', function(e) {
			marker_pano_open[photo.photo_id] = marker;
			infowindow.setZIndex(++infowindowLevel);
			infowindow.open(carteContactMap,marker);
		});
		google.maps.event.addListener(infowindow, 'closeclick', function(e) {
				delete marker_pano_open[photo.photo_id];
		});
	}
	if (data.photos && data.photos.length) {
		for (var i = 0; i < data.photos.length; i++) {
			var photo = data.photos[i]; 

			// Pour un gain de vitesse et de confort visuel conserve les marqueurs existant
			if (photo.photo_id in markers_pano) {
				new_markers_pano[photo.photo_id] = markers_pano[photo.photo_id];
			} else {
				// crée les nouveaux marqueurs
				var maLatLng = new google.maps.LatLng(photo.latitude, photo.longitude);
				marker_pano = new google.maps.Marker({
					map: carteContactMap,
					position: maLatLng,
					title: photo.photo_title,
					icon: photo.photo_file_url
				});
				attachinfowindowPanoramino(marker_pano,photo);
				new_markers_pano[photo.photo_id] = marker_pano;
			};
		}
	}
	// supprime les anciens marqueurs
	if (delete_old_pano) {
		for (var photo_id in markers_pano) {
			if ((!(photo_id in new_markers_pano))&&(!(photo_id in marker_pano_open))) {
				markers_pano[photo_id].setMap(null);
				delete markers_pano[photo_id];
			}
		}
	}
	//garde en memoire les marqueurs dont l'infowindow est ouverte
	for (var photo_id in marker_pano_open) {
		new_markers_pano[photo_id] = marker_pano_open[photo_id];
	}

	markers_pano = new_markers_pano;
} 

function delete_markers_pano(){
	for (var photo_id in markers_pano) {
		markers_pano[photo_id].setMap(null);
		delete markers_pano[photo_id];
	}
}

function show_markers_pano(){
	loadPhotosPanoramino();
}

function activate_pano(){
	activated_panoramino = true;
	loadPhotosPanoramino();
}
 
function desactivate_pano(){
	activated_panoramino = false;
	delete_markers_pano();
}

function inverse_pano(){
	if (!activated_panoramino) {
		activate_pano();
	}else{
		desactivate_pano();
	}
}
/**************************************************************/
/* Charge les données Wikipédia contenu dans la carte visible */
/**************************************************************/
var geoXml;
/*
function loadDataWikipedia() {
      geoXml = new geoXML3.parser({
        zoom: true,
        processStyles: true,
        markerOptions: {map: carteContactMap, shadow: null},
        infoWindowOptions: {pixelOffset: new google.maps.Size(0, 12)},
        singleInfoWindow: true,
        createMarker: addMarker,
        afterParse: parsed,
        failedParse: failed
      });
alert('tutu');
	var bounds = carteContactMap.getBounds();
	var url = 'data/wikipedia_bounds.kml.php?maxRows=10&west=' + 
	mapBounds.getSouthWest().lng().toFixed(6) + '&north=' + 
	mapBounds.getNorthEast().lat().toFixed(6) + '&east=' + 
	mapBounds.getNorthEast().lng().toFixed(6) + '&south=' +  
	mapBounds.getSouthWest().lat().toFixed(6);

	// Load the KML - new markers will be added when it returns
	alert(geoXml.parse(url));
}
function addMarker(placemark) {
  var coordinates = new google.maps.LatLng(placemark.point.lat, placemark.point.lng);
  for (var m = markers.length - 1; m >= 0; m--) {
	if (markers[m].get_position().equals(coordinates)) {
	  return;
	}
  }

  var marker = geoXml.createMarker(placemark);
  markers.push(marker);
};
*/

/*********************************************************/
/* Calcul l'itinéraire                                   */
/*********************************************************/
function CalculRoute(num) {
	if (document.getElementById("select_from"+num).value != "") { 
		fromAddress = document.getElementById("select_from"+num).value; 
	} else {
		fromAddress = document.getElementById("text_from"+num).value; 
	}; 
	if (document.getElementById("select_to"+num).value != "") { 
		toAddress = document.getElementById("select_to"+num).value; 
	} else { 
		toAddress = document.getElementById("text_to"+num).value; 
	}; 
	var request = {
		origin:fromAddress, 
		destination:toAddress,
		travelMode: google.maps.DirectionsTravelMode.DRIVING
	};
	directionsService.route(request, function(response, status) {
	  if (status == google.maps.DirectionsStatus.OK) {
		directionsDisplay.setDirections(response);
	  }
	});
}

/*********************************************************/
/* Affiche le menu more options                          */
/*********************************************************/
var chicago = new google.maps.LatLng(41.850033, -87.6500523);

// Define a property to hold the More state
MoreControl.prototype.home_ = null;

// Define setters and getters for this property
MoreControl.prototype.getMore = function() {
    control.setMorePano.style.display = 'block';
	alert('fc_getmore');
  //return this.home_;
}

MoreControl.prototype.setMore = function(home) {
  alert('fc_setMore');
  //this.home_ = home;
}

function MoreControl(map, div) {
	var visible = false;

  // Get the control DIV. We'll attach our control UI to this DIV.
  var controlDiv = div;

  // We set up a variable for the 'this' keyword since we're adding event
  // listeners later and 'this' will be out of scope.
  var control = this;

  // Set CSS styles for the DIV containing the control. Setting padding to
  // 5 px will offset the control from the edge of the map
  controlDiv.style.padding = '5px';

  // Set CSS for the control border
  var MoreUI = document.createElement('DIV');
  MoreUI.style.backgroundColor = 'white';
  MoreUI.style.borderStyle = 'solid';
  MoreUI.style.borderWidth = '2px';
  MoreUI.style.cursor = 'pointer';
  MoreUI.style.textAlign = 'center';
  MoreUI.style.width = '120px';
  MoreUI.title = 'Click to set 1';
  controlDiv.appendChild(MoreUI);

  // Set CSS for the control interior
  var MoreText = document.createElement('DIV');
  MoreText.style.fontFamily = 'Arial,sans-serif';
  MoreText.style.fontSize = '12px';
  MoreText.style.paddingLeft = '4px';
  MoreText.style.paddingRight = '4px';
  MoreText.style.color = 'black';
  MoreText.innerHTML = '<b>More</b><img style="position: absolute; right: 9px; top: 9px; display: block; " src="http://maps.gstatic.com/intl/fr_ALL/mapfiles/down-arrow.gif">';
  MoreUI.appendChild(MoreText);
  
  // Set CSS for the setMore control border
  var MoreDisplayUI = document.createElement('DIV');
  MoreDisplayUI.style.backgroundColor = 'white';
  MoreDisplayUI.style.borderStyle = 'solid';
  MoreDisplayUI.style.borderWidth = '1px';
  MoreDisplayUI.style.cursor = 'pointer';
  MoreDisplayUI.style.textAlign = 'center';
  MoreDisplayUI.style.fontFamily = 'Arial,sans-serif';
  MoreDisplayUI.style.fontSize = '12px';
  MoreDisplayUI.style.paddingLeft = '4px';
  MoreDisplayUI.style.paddingRight = '4px';
  MoreDisplayUI.style.color = 'black';
  MoreDisplayUI.style.display = 'none';
  MoreDisplayUI.title = 'Click to set 2';
  controlDiv.appendChild(MoreDisplayUI);

  // Set CSS for the control interior
  var Pano = document.createElement('DIV');
  var Vchecked = "";
  if (activated_panoramino) Vchecked = "checked";
  Pano.innerHTML = '<input name="mark_contactmap" type="checkbox" '+Vchecked+' onclick="inverse_pano()" /> Panoramino <br />';
  MoreDisplayUI.appendChild(Pano);

/*  var setMoreText = document.createElement('DIV');
  setMoreText.innerHTML = '<input name="mark_contactmap" type="checkbox" onclick="switchLayer_contactmap(this.checked, layers_contactmap[0].obj)" /> Photos <br />';
  MoreDisplayUI.appendChild(setMoreText);
*/
  // Setup the click event listener for More:
  google.maps.event.addDomListener(MoreUI, 'click', function() {
	if (visible) {
		MoreDisplayUI.style.display = 'none';
		visible = false;
	}else{
		MoreDisplayUI.style.display = 'block';
		visible = true;
	}
  });

}

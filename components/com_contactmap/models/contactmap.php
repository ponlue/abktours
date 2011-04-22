<?php
    /*
    * ContactMap Component Google Map for Joomla! 1.5.x
    * Version 3.11
    * Creation date: Janvier 2011
    * Author: Fabrice4821 - www.gmapfp.org
    * Author email: webmaster@gmapfp.org
    * License GNU/GPL
    */

defined('_JEXEC') or die();
jimport('joomla.application.component.model');

class ContactMapsModelContactMap extends JModel
{
    function __construct() 
    {
        parent::__construct(); 
        global $mainframe; 
        $this->_catid = JRequest::getVar('catid', 0, '', 'int'); 
        $this->_id = JRequest::getVar('id', 0, '', 'int'); 
        $this->_perso = JRequest::getVar('id_perso', 0, '', 'int'); 

        $this->_total   = null; 
        $this->_result  = null; 
        $this->_result2 = null; 
        $this->_result_personnalisation = null; 
        $this->_num_marqueurs = 0;

        $config =& JComponentHelper::getParams('com_contactmap'); 

        $lang = JFactory::getLanguage(); 
        $tag_lang=(substr($lang->getTag(),0,2)); 
         
        JHTML::_( 'behavior .mootools' );

        $mainframe->addCustomHeadTag( '<link rel="stylesheet" href="'.JURI::base().'components/com_contactmap/views/contactmap/contactmap.css" type="text/css" />'); 
        $mainframe->addCustomHeadTag( '<link rel="stylesheet" href="'.JURI::base().'components/com_contactmap/views/contactmap/contactmap2.css" type="text/css" />'); 

        if (!defined( '_JOS_GMAPFP_LIGHTBOX' ))
        {
            /** verifi que la fonction n'est défini qu'une faois */
            define( '_JOS_GMAPFP_LIGHTBOX', 1 );    
            
            $mainframe->addCustomHeadTag( '<link rel="stylesheet" type="text/css" href="'.JURI::base().'components/com_contactmap/floatbox/floatbox.css" />');
            $mainframe->addCustomHeadTag( '<script type="text/javascript" src="'.JURI::base().'components/com_contactmap/floatbox/floatbox.js"></script>');
        }
        
        if (!defined( '_JOS_GMAPFP_APIV3' ))
        {
            /** verifi que la fonction n'est défini qu'une faois */
            define( '_JOS_GMAPFP_APIV3', 1 );
            $mainframe->addCustomHeadTag( '<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />'); 
            $mainframe->addCustomHeadTag( '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true&language='.$tag_lang.'"></script>'); 
            $mainframe->addCustomHeadTag( '<script type="text/javascript" src="'.JURI::base().'components/com_contactmap/libraries/map.js"></script>'); 
        }
		//include(JURI::base().'components\com_contactmap\libraries\SimpleImage.php');
    }

    function _getQuery()
    {
        $db     = JFactory::getDBO();
        
        global $mainframe, $option, $Itemid;
        $params = clone($mainframe->getParams('com_contactmap'));
        $tri = $params->get('orderby_pri');

        switch ($tri) {
        case 'alpha' :
            $order = "\n ORDER BY a.name";
            break;
        case 'ralpha' :
            $order = "\n ORDER BY a.name DESC";
            break;
        case 'ville' :
            $order = "\n ORDER BY a.suburb, a.name";
            break;
        case 'rville' :
            $order = "\n ORDER BY a.suburb DESC, a.name DESC";
            break;
        case 'pays' :
            $order = "\n ORDER BY a.country, a.suburb, a.name";
            break;
        case 'rpays' :
            $order = "\n ORDER BY a.country DESC, a.suburb DESC, a.name DESC";
            break;
        default :
            $order = "\n ORDER BY a.ordering";
            break;
        }

        $select = 'a.*, cc.access as category_access, cc.title as category_name, cc.image as cat_image, cc.image_position, cc.description as cat_description,'
        . ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug, '
        . ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(\':\', cc.id, cc.alias) ELSE cc.id END AS catslug ';
        $from   = '#__contact_details AS a';

        $joins[] = 'INNER JOIN #__categories AS cc on cc.id = a.catid';

        $wheres[] = 'a.published = 1';
        $wheres[] = 'cc.published = 1';
        
        $user =& JFactory::getUser();
        $aid = $user->get('aid', 0);
        $wheres[] = 'cc.access <= '.(int) $aid;
        
        if (!empty($this->_catid))
        {
            $_catids = $this->verif_catid($this->_catid);
            $_catids = explode( ',', $_catids );
            foreach ($_catids as $_catid)
            {
                $wheresOr[] = 'a.catid = '.$_catid.'';
            }
            $wheres[] = '('.implode( "\n OR ", $wheresOr).')';
        };

        if (!empty($this->_id))
        {
            $wheres[] = 'a.id = '.$this->_id.'';
        };

        $query = "SELECT " . $select .
                "\n FROM " . $from .
                ' '. implode ( ' ', $joins ) .
                "\n WHERE " . implode( "\n  AND ", $wheres ).
                $order;

        return $query;
    }

    function verif_catid($id)
    {
        $db         = JFactory::getDBO();

        $query = "SELECT params".
                "\n FROM #__categories".
                "\n WHERE id = " .$id.
                "\n AND section = 'com_contact_details'";
        $db->setQuery( $query );
        $list_id = $db->loadObject();
        if (!empty($list_id)) {
            return $list_id->params;
        } else {
            return $id;
        }
    }
    
    function getTotal()
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_total))
        {
            $query = $this->_getQuery();
            $this->_total = $this->_getListCount($query);
        }
        return $this->_total;
    }
    
    function getRef($id)
    {
    $Ref='<div><a href="http://tourisme-chateauneufsurloire.fr" title="chateauneuf sur loire">chateauneuf sur loire</a><a href="http://tourisme-chateauneufsurloire.fr" title="tourisme France">tourisme France</a><a href="http://www.fay-aux-loges-cpa.fr" title="Fay">Fay</a> <a href="http://www.fay-aux-loges-cpa.fr" title="Canal d Orl&eacute;ans">Canal d&rsquo;Orl&eacute;ans</a></div>';
    return $Ref;
    }
    
    function getContactMapList( $options=array() )
    {
        if (empty($this->_result2))
        {
            $query  = $this->_getQuery( $options );
            $this->_result2 = $this->_getList( $query );
        }
        return @$this->_result2;
    }
    
    function getView()
    {
        if (empty($this->_result)) 
        {
        $query  = $this->_getQuery();
        $this->_result = $this->_getList( $query );
        };
        $map    = $this->getCarte($this->_result);
        return $map;
    }
    
    function getCarte ($rows)
    {
    //spécial pour ContactMap
    //contactmap_eventcontrol="0"
    $click_over=2;

    global $mainframe; 
    $language =& JFactory::getLanguage();
    $language->load('com_contactmap');
    $config =& JComponentHelper::getParams('com_contactmap');
    //spécial pour ContactMap
    //$perso = $this->getPersonnalisation();
	if ($config->get('contactmap_photo_icon')) $this->verif_icon($rows);


    //traitement des données de zoom
    $_layout = JRequest::getVar('layout', "", '', 'str');
    $zoom = "";
    if ($_layout == "item_carte") {
        if ((empty($this->_id))or($config->get('contactmap_zoom_lightbox_carte')!=100)) {
            $zoom = $config->get('contactmap_zoom_lightbox_carte');
        }else{
            $zoom = $rows[0]->gzoom;
        };
    };
    if ($_layout == "print_article") {
        $zoom = $config->get('contactmap_zoom_lightbox_imprimer');
    };
    if (($zoom == "") or ($zoom == 0)) {
        $zoom = $config->get('contactmap_zoom');
    };
    if (!($zoom)) { $zoom = 2;};
    $Zmap = $zoom;

    $flag = JRequest::getVar('flag', 0, '0', 'int');
    $itemid = JRequest::getVar('Itemid', 0, '', 'int');

    if (empty($num)) { $num = ''; };

//Création des marqueurs
	//creation de l'infowindow
    //si on_click
    if ($_layout == "map") {
        $create_infowindow='
        function attachinfowindow(marker,place){
            google.maps.event.addListener(marker, \'click\', function(e) {
                fb.start({ href:place[5], rev: "width:70% height:70% scrolling:yes innerBorder:1" });
            });
            var infowindow = new google.maps.InfoWindow({
                content: place[4]
            });
            google.maps.event.addListener(marker, \'mouseover\', function(e) {
                infowindow.setZIndex(++infowindowLevel);
                infowindow.open(carteContactMap'.$num.',marker);
            });
        }';
        $plus_detail = JTEXT::_("CONTACTMAP_CLICK_SEND_MAIL");
    }else{
        $create_infowindow='function attachinfowindow(marker,place){
            var infowindow = new google.maps.InfoWindow({
                content: place[4]
            });
            google.maps.event.addListener(marker, \'click\', function(e) {
                infowindow.setZIndex(++infowindowLevel);
                infowindow.open(carteContactMap'.$num.',marker);
            });
        }';
        $plus_detail = "";

    }
	//Centrage auto et zoom auto
    if ($config->get('contactmap_auto'))
        {$centrageauto='carteContactMap.setCenter(bounds_ContactMap.getCenter());';}
    else
        {$centrageauto='';}
    
    if ($config->get('contactmap_auto_zoom')!=0) {
		$Zauto='carteContactMap.fitBounds(bounds_ContactMap);';}
    else
        {$Zauto='';}
    
	//création du marqueur
	$create_marker = 'create_carteContactMap.prototype.addMarker = function 	(donnees){'
		.$create_infowindow
		.'	
	for (var i = 0; i < donnees.length; i++) {
		var place = donnees[i];
		hm=markerImage[i].height;
		lm=markerImage[i].width;
		image[i] = new google.maps.MarkerImage(markerImage[i].src,
			// This marker is 20 pixels wide by 32 pixels tall.
			new google.maps.Size(lm, hm),
			// The origin for this image is 0,0.
			new google.maps.Point(0,0),
			// The anchor for this image is the base of the flagpole at 0,32.
			new google.maps.Point((lm/2), hm));

		var maLatLng = new google.maps.LatLng(place[1], place[2]);
		bounds_ContactMap.extend(maLatLng);
		marker_map[i] = new google.maps.Marker({
			map: carteContactMap'.$num.',
			position: maLatLng,
			title: place[7],
			icon: image[i],
			zIndex: place[3]
		});
		attachinfowindow(marker_map[i],place);
	}
	'
	.$centrageauto
	.$Zauto
	.'
};

';

    $VariableDirection='';
    $DeclareDirection='';
    if ($config->get('contactmap_itineraire')) {
        $VariableDirection='var directionDisplay;
            var directionsService = new google.maps.DirectionsService();';
        $DeclareDirection='
            directionsDisplay = new google.maps.DirectionsRenderer();
            directionsDisplay.setMap(carteContactMap'.$num.');
                directionsDisplay.setPanel(document.getElementById("contactmap_directions'.$num.'"));';
    }
    $MapVariables=' var map'.$num.';
            var markerImage = new Array();
            var places  = [];
            var image   = new Array();
            var infowindowLevel = 0;
            '.$VariableDirection.'
    ';

//affichage de plus de détail : Panoramino, Wiki, Youtube, ...
	$control ="";
	$event_panoramino = "";
    if ($config->get('contactmap_plus_info')){
		$control = '
		  var moreControlDiv = document.createElement(\'DIV\');
		  var moreControl = new MoreControl(carteContactMap'.$num.', moreControlDiv);
		  moreControlDiv.index = 1;
		  carteContactMap'.$num.'.controls[google.maps.ControlPosition.TOP_RIGHT].push(moreControlDiv);
		';

	//photos Panoramino
	$event_panoramino = "
		activated_panoramino = false;
		nbre_photos_panoramino = 20;
		google.maps.event.addListener(carteContactMap, 'idle', function() {
		  if (activated_panoramino) {loadPhotosPanoramino();}
		});
	";
	}
    $carte='var ref = \''.$this->getRef('id').'\'; ';
    
//choix des options d'affichage de la carte
//bar de zoom 3D (ANDROID,SMALL,ZOOM_PAN)
    if (($config->get('contactmap_mapcontrol')==1)) 
        {$ControlOption='navigationControl: true,
                  navigationControlOptions: {style: google.maps.NavigationControlStyle.ZOOM_PAN},';}
    else
        {$ControlOption='navigationControl: false,';};
//Affichage de l'échelle
    if (($config->get('contactmap_scalecontrol')==1)) 
        {$ControlOption.='
              scaleControl: true,';}
    else
        {$ControlOption.='
              scaleControl: false,';};
//activation du zoom par molette de la sourie
    if (($config->get('contactmap_mousewheel')==1)) 
        {$ControlOption.='
              scrollwheel: true,';}
    else
        {$ControlOption.='
              scrollwheel: false,';};
              
//affichage streetView
    if($config->get('contactmap_height_sv')){
        $HeightPano=$config->get('contactmap_height_sv');
    }else{
        $HeightPano='300';};
    $streeview='';
    if ($config->get('contactmap_streetView')) 
        { $ControlOption.='
              streetViewControl: true,';
        $streeview='
            panorama = new  google.maps.StreetViewPanorama(document.getElementById("pano"));
                carteContactMap.setStreetView(panorama);
                panorama.setVisible(false);';
        $streeview.='
            google.maps.event.addListener(panorama, \'visible_changed\', function(mEvent) {
                if(panorama.getVisible()) {
                    var maDiv = document.getElementById("pano");
                    maDiv.style.height = "'.$HeightPano.'px";
                } else {
                    var maDiv = document.getElementById("pano");
                    maDiv.style.height = "1px";
                };
            });';}
    else
        { $ControlOption.='
              streetViewControl: false,';};

//insertion du fichier kml
    $geo_xml='';
    if ($config->get('contactmap_geoXML')) 
        { $geo_xml= '
            var ctaLayer = new google.maps.KmlLayer(\''.$config->get('contactmap_geoXML').'\');
                ctaLayer.setMap(carteContactMap'.$num.');';};

//insertion du traffic
    $traffic='';
    if ($config->get('contactmap_traffic')) 
        { $traffic= '
            var trafficLayer = new google.maps.TrafficLayer();
                trafficLayer.setMap(carteContactMap'.$num.');';};

$mapTypeId=array();
//affichage bouton carte hybride
    if ($config->get('contactmap_hybrid')) 
        { $mapTypeId[]='google.maps.MapTypeId.HYBRID';}
//affichage bouton carte normale
    if ($config->get('contactmap_normal')) 
        { $mapTypeId[]='google.maps.MapTypeId.ROADMAP';}
//affichage bouton carte relief
    if ($config->get('contactmap_physic')) 
        { $mapTypeId[]='google.maps.MapTypeId.TERRAIN';}
//affichage bouton carte satellite
    if ($config->get('contactmap_satellite')) 
        { $mapTypeId[]='google.maps.MapTypeId.SATELLITE';}
    $mapTypeIds=implode( ",", $mapTypeId );
    $mapTypeIds='var types = ['.$mapTypeIds.'];';
//selection du type d'affichage des types de carte
    $MapTypeControlStyle='';
    if ($config->get('contactmap_vertical')) 
        { $MapTypeControlStyle='style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,';}


    $carte.="\n\n";
    $points = "places".$num." = [\n";
    $cesure = $config->get('contactmap_taille_bulle_cesure');
    $cnt2 = 0;
    foreach($rows as $row){
        if (($row->glat<>"")&&($row->glng<>"")) {
			if ($cnt2) {$points.=',';};
			$points.='[\''.$row->marqueur.'\'';
			$cnt2++;

            $image ='';
            if (@$row->image) {
                $image = JURI::base().'/images/stories/'.$row->image;
                $image = "<img src=".$image." height=".$config->get('contactmap_hauteur_img')."px>";
            }
            $points.= ','.$row->glat.','.$row->glng.',';
            if (@$row->misc) {$intro=substr($row->misc,0,-3);}else{$intro='';};
            if (@$row->message) {$message=substr($row->message,1);}else{$message='';};
            $text = strip_tags(addslashes($intro.$message));
            $text = str_replace(chr(10), '',$text);
            $text = str_replace(chr(13), '<BR />',$text);
            $nom  = addslashes($row->name);
            
            $link4=substr($row->link,0,4);
            $link5=substr($row->link,0,5);
            $link9=substr($row->link,0,9);
            $linkmap=$row->link;
            
            if ($link4=="www.") {$linkmap="http://".$linkmap;};
            
            if ((empty($row->link))||($row->link='')) {
                if ($config->get('target')==0) {
                    $map_link=JURI::base()."index.php?option=com_contactmap&view=contactmap&tmpl=component&id=".$row->id."&flag=1";
                }else{
                    $map_link=JURI::base()."index.php?option=com_contactmap&view=contactmap&id=".$row->id."&flag=1";
                };
                $choix=1;
            } else {
                if (($link5=="http:")||($link4=="www.")||($link9=="index.php")) {
                    $map_link=$linkmap;
                    if (($link5=="http:")||($link4=="www.")) {$choix=0;}else{$choix=1;};
                } else {
                    if ($config->get('target')==0) {
                        $map_link=JURI::base()."index.php?option=com_content&tmpl=component&view=article&id=".$row->article_id."&Itemid=".$itemid;
                    }else{
                        $map_link=JURI::base()."index.php?option=com_content&view=article&id=".$row->article_id."&Itemid=".$itemid;
                    };
                    $choix=1;
                };
            };
           /* if (@$row->icon)
                if($config->get('target')==0) {
                    $map_link=JRoute::_('index.php?option=com_contactmap&view=contactmap&Itemid='.$itemid.'&layout=article&tmpl=component&id='.$row->id, false);
                }else{
                    $map_link=JRoute::_('index.php?option=com_contactmap&view=contactmap&Itemid='.$itemid.'&layout=article&id='.$row->slug, false);
                };
            };*/
            
            if (@$row->address)     {
                $adr    = addslashes($row->address)."<br />";
                $order  = array("\r\n", "\n", "\r");
                $adr    = str_replace( $order, '<br />', $adr );
                $adr    = str_replace( '<br>', '<br />', $adr );
            }else{
                $adr='';
            };
            if (@$row->postcode)    {$cp    = addslashes($row->postcode)."<br />";}else{$cp='';};
            if (@$row->suburb)      {$ville = addslashes($row->suburb)."<br />";}else{$ville='';};
            if (@$row->state)       {$dep   = addslashes($row->state)."<br />";}else{$dep='';};
            if (@$row->country)     {$pays  = addslashes($row->country)."<br />";}else{$pays='';};
            
            switch ($row->affichage) {
            case 0: 
                $points.= ($this->_num_marqueurs+1000).", \"<div class='contactmap_marqueur' style='width:".$config->get('contactmap_width_bulle_contactmap')."px'><span>".$nom."</span><br /><br />".$image."<table><table><tr><td>".$adr.$cp.$ville.$dep.$pays."</td></tr></table><tr><td>".substr($text,0,$cesure);
                if (strlen($text)>$cesure) { $points.="..."; };
                $points.="</td></tr></table><br />".$plus_detail."</div>\",\"".$map_link."\",".$choix;
                break;
            case 1:
                $points.= ($this->_num_marqueurs+1000).", \"<div class='contactmap_marqueur' style='width:".$config->get('contactmap_width_bulle_contactmap')."px'><span>".$nom."</span><br /><br />".$image."<table><tr><td>".$adr.$cp.$ville.$dep.$pays."</td></tr></table><br />".$plus_detail."</div>\",\"".$map_link."\",".$choix;
                break;
            case 2:
                $points.= ($this->_num_marqueurs+1000).", \"<div class='contactmap_marqueur' style='width:".$config->get('contactmap_width_bulle_contactmap')."px';><span>".$nom."</span><br /><br />".$image.substr($text,0,$cesure);
                if (strlen($text)>$cesure) { $points.="..."; };
                $points.="<br />".$plus_detail."</div>\",\"".$map_link."\",".$choix;
                break;
            default:
                $points.= ($this->_num_marqueurs+1000).", \"<div class='contactmap_marqueur' style='width:".$config->get('contactmap_width_bulle_contactmap')."px';><span>".$nom."</span>";
                $points.="<br />".$plus_detail."</div>\",\"".$map_link."\",".$choix;
            }
            if (!((($config->get('contactmap_eventcontrol')==1)AND($click_over==0))OR($click_over==1))){
                $points.=", \"".$nom."\"";
            };          
            $points.="]\n ";
            $this->_num_marqueurs++;
        }
    }
    $points.='];';
    
    $carte_choix='ROADMAP';
    if ($config->get('contactmap_choix_affichage_carte')==2) { $carte_choix='SATELLITE';};
    if ($config->get('contactmap_choix_affichage_carte')==3) { $carte_choix='HYBRID';};
    if ($config->get('contactmap_choix_affichage_carte')==4) { $carte_choix='TERRAIN';};

    $gestion_erreur_direction='';
    if ($config->get('contactmap_itineraire')) {
        $gestion_erreur_direction='GEvent.addListener(gdir'.$num.', "error", handleErrors'.$num.');';
    };
    
    if ($config->get('contactmap_centre_lat')) {
        $lat=$config->get('contactmap_centre_lat');
    }else{
        $lat='0';};
    if ($config->get('contactmap_centre_lng')) {
        $lng=$config->get('contactmap_centre_lng');
    }else{
        $lng='0';};
    
    $mainframe->addCustomHeadTag( '<script type="text/javascript"> 
        '.
        $MapVariables.
        '
		var carteContactMap;
		
        function create_carteContactMap'.$num.'() {
            '.$mapTypeIds.'
            var myOptions'.$num.' = {
              zoom: '.$Zmap.',
              center: new google.maps.LatLng('.$lat.', '.$lng.'),
              '.$ControlOption.'
              mapTypeControlOptions: {
                '.$MapTypeControlStyle.'
                mapTypeIds: types
              },
              mapTypeId: google.maps.MapTypeId.'.$carte_choix.'
            };
            carteContactMap'.$num.' = new google.maps.Map(document.getElementById("map_canvas'.$num.'"),myOptions'.$num.');'
			.$control
            .$geo_xml
			.$event_panoramino
            .$traffic
            .$streeview
            .$DeclareDirection
//          .$gestion_erreur_direction
//          .$carte
        ."\n}\n\n"
		.$create_marker
        .$points."\n"

/*        .'function handleErrors'.$num.'(){
            if (gdir.getStatus().code == G_GEO_UNKNOWN_ADDRESS)
                alert("'.JText::_( 'CONTACTMAP_G_GEO_UNKNOWN_ADDRESS' ).'\n'.JText::_( 'CONTACTMAP_CODE_ERREUR' ).' " + gdir.getStatus().code);
            else if (gdir.getStatus().code == G_GEO_SERVER_ERROR)
                alert("'.JText::_( 'CONTACTMAP_G_GEO_SERVER_ERROR' ).'\n'.JText::_( 'CONTACTMAP_CODE_ERREUR' ).' " + gdir.getStatus().code);
            else if (gdir.getStatus().code == G_GEO_MISSING_QUERY)
                alert("'.JText::_( 'CONTACTMAP_G_GEO_MISSING_QUERY' ).'\n'.JText::_( 'CONTACTMAP_CODE_ERREUR' ).' " + gdir.getStatus().code);
            else if (gdir.getStatus().code == G_GEO_BAD_KEY)
                alert("'.JText::_( 'CONTACTMAP_G_GEO_BAD_KEY' ).'\n'.JText::_( 'CONTACTMAP_CODE_ERREUR' ).' " + gdir.getStatus().code);
            else if (gdir.getStatus().code == G_GEO_BAD_REQUEST)
                alert("'.JText::_( 'CONTACTMAP_G_GEO_BAD_REQUEST' ).'\n'.JText::_( 'CONTACTMAP_CODE_ERREUR' ).' " + gdir.getStatus().code);
            else alert("'.JText::_( 'CONTACTMAP_G_GEO_ELSE' ).'");
        }'
*/        );
    
    if (empty($Hmap)) {$Hmap=$config->get('contactmap_height');};
    $carte='';
    if (!empty($perso)) { $carte=$perso->intro_carte;};
    $carte.=
    '<div id="contactmap" style="width:100%; height:auto">';
    $carte.=
        '<div id="fpgmap"><a href="http://tourisme-chateauneufsurloire.fr" title="chateauneuf">chateauneuf</a><a href="http://www.fay-aux-loges-cpa.fr" title="Fay aux loges">Fay aux loges</a> <a href="http://www.fay-aux-loges-cpa.fr" title="Canal d Orl&eacute;ans">Canal d Orl&eacute;ans</a></div>';
    $carte.=
        '<div id="map_canvas'.$num.'" style="width:100%; height:'.$Hmap.'px"></div>';
    if ($config->get('contactmap_streetView')) {
        $carte.=
         '<div id="pano" style="width:100%;"></div> ';
    }

            if (($config->get('contactmap_itineraire')==1)) {
            $carte.='                   
                <p style="text-align:center;margin:2px 0;">&nbsp;</p>
                <form action="#" onsubmit="CalculRoute(\''.$num.'\'); return false;" method="post" name="direction_form">
                        <div class="gmnoprint">
                        <legend>'.JText::_( 'CONTACTMAP_DIRECTION' ).'</legend>
                        <p>
                            '.JText::_( 'CONTACTMAP_DE' ).' : 
                            <select name="select_from'.$num.'" id="select_from'.$num.'">
                                <option value="">'.JText::_( 'CONTACTMAP_CHOIX_DE' ).'</option>';
                                foreach($rows as $row) {
                                    $selected = '';
                                    if (isset($row->glat) && isset($row->glng) && !empty($row->glat) && !empty($row->glng)) {
                                        $value = $row->glat.','.$row->glng;
                                    } else {
                                        $value = @$row->country.' '.@$row->postcode.' '.@$row->suburb.' '.@$row->address;
                                    }
                                    $carte .= '<option value="'.$value.'" '.$selected.'>'.$row->name.'</option>';
                                }
                                $carte.='
                            </select>
                            '.JText::_( 'CONTACTMAP_OU' ).'<input type="text" name="text_from'.$num.'" id="text_from'.$num.'" />
                        </p>
                            '.JText::_( 'CONTACTMAP_VERS' ).' :
                            <select name="select_to'.$num.'" id="select_to'.$num.'">
                                <option value="">'.JText::_( 'CONTACTMAP_CHOIX_VERS' ).'</option>';
                                foreach($rows as $row) {
                                    $selected = '';
                                    if (isset($row->glat) && isset($row->glng) && !empty($row->glat) && !empty($row->glng)) {
                                        $value = $row->glat.','.$row->glng;
                                    } else {
                                        $value = @$row->country.' '.@$row->postcode.' '.@$row->suburb.' '.@$row->address;
                                    }
                                    $carte .= '<option value="'.$value.'" '.$selected.'>'.$row->name.'</option>';
                                }
                                $carte.='
                            </select>
                            '.JText::_( 'CONTACTMAP_OU' ).'<input type="text" name="text_to'.$num.'" id="text_to'.$num.'" />
                            <input type="submit" value='.JText::_( 'CONTACTMAP_GO' ).' />
                        </div>
                        <div id="contactmap_directions'.$num.'"></div>
                <input type="hidden" name="direction" value="1" />
                </form>
                ';
            };
    $carte.='</div>';
            
    $loadmarqueur = "
		function LoadMarqueur".$num."() {";
    $cnt = 1;
    foreach($rows as $row) {
        $loadmarqueur .="
            markerImage[".($cnt-1)."] = new Image(); ";
        if (($row->image)&&($config->get('contactmap_photo_icon'))) {
			$image = JURI::base().'images/stories/thumb_'.$row->image;
			$loadmarqueur .=" markerImage[".($cnt-1)."].src = \"".$image."\";";
		}else{
			$loadmarqueur .=" markerImage[".($cnt-1)."].src = \"".$row->marqueur."\";";
		}
        $cnt++;
    }

    // Charge la procédure d'init de la carte
    $mainframe->addCustomHeadTag( "
    ".$loadmarqueur."
	setTimeout(initialise , 500);
    }
	function initialise() {
		var maCarteContactMap= new create_carteContactMap;
		maCarteContactMap.addMarker(places);

	}		
			
	google.maps.event.addDomListener(window, 'load', LoadMarqueur".$num.");
    </script>");

    return $carte;
    }
    function getlistville()
    {
        $wheres[] = 'published = 1';

        if (!empty($this->_catid))
        {
            $_catids = $this->verif_catid($this->_catid);
            $_catids = explode( ',', $_catids );
            foreach ($_catids as $_catid)
            {
                $wheresOr[] = 'catid = '.$_catid.'';
            }
            $wheres[] = '('.implode( " OR ", $wheresOr).')';
        };

        $query = 'SELECT DISTINCT suburb' .
                ' FROM #__contact_detail' .
                ' WHERE ' . implode( '  AND ', $wheres ).
                ' ORDER BY suburb';

        return $this->_getList( $query );
    }

    function getlistdepartement()
    {
        $wheres[] = 'published = 1';

        if (!empty($this->_catid))
        {
            $_catids = $this->verif_catid($this->_catid);
            $_catids = explode( ',', $_catids );
            foreach ($_catids as $_catid)
            {
                $wheresOr[] = 'catid = '.$_catid.'';
            }
            $wheres[] = '('.implode( " OR ", $wheresOr).')';
        };

        $query = 'SELECT DISTINCT state' .
                ' FROM #__contact_detail' .
                ' WHERE ' . implode( '  AND ', $wheres ).
                ' ORDER BY state';
        return $this->_getList( $query );
    }

    function getlistpays()
    {
        $wheres[] = 'published = 1';

        if (!empty($this->_catid))
        {
            $_catids = $this->verif_catid($this->_catid);
            $_catids = explode( ',', $_catids );
            foreach ($_catids as $_catid)
            {
                $wheresOr[] = 'catid = '.$_catid.'';
            }
            $wheres[] = '('.implode( " OR ", $wheresOr).')';
        };

        $query = 'SELECT DISTINCT country' .
                ' FROM #__contact_detail' .
                ' WHERE ' . implode( '  AND ', $wheres ).
                ' ORDER BY country';
        return $this->_getList( $query );
    }

    function getlistcategorie()
    {
        $wheres[] = 'published = 1';

        if (!empty($this->_catid))
        {
            $_catids = $this->verif_catid($this->_catid);
            $_catids = explode( ',', $_catids );
            foreach ($_catids as $_catid)
            {
                $wheresOr[] = 'id = '.$_catid.'';
            }
            $wheres[] = '('.implode( " OR ", $wheresOr).')';
        };

        $user =& JFactory::getUser();
        $aid = $user->get('aid', 0);
        $wheres[] = 'access <= '.(int) $aid;

        $query = 'SELECT DISTINCT title' .
                ' FROM #__categories' .
                ' WHERE section = "com_contact_details"' .
                ' AND ' . implode( '  AND ', $wheres ).
                ' ORDER BY title';
        return $this->_getList( $query );
    }

	function verif_icon($thumbs)
	{
		jimport('joomla.filesystem.file');
		foreach ($thumbs as $thumb)
		{
            $image ='';
            if (@$thumb->image) {
				$filepath = JPath::clean(JPATH_ROOT.DS.'images'.DS.'stories'.DS.$thumb->image);
				$thumbpath = JPath::clean(JPATH_ROOT.DS.'images'.DS.'stories'.DS.'thumb_'.$thumb->image);
				if (!file_exists($thumbpath))
				{
					$image = new Resize_Image();
					$image->load($filepath);
					$image->resizeToHeight(32);
					$image->save($thumbpath);
					$image->destroy();
				}
            }
		}
	}
}

class Resize_Image {
   var $image;
   var $image_type;
 
   function destroy() {
   		imagedestroy($this->image);
   }
   
   function load($filename) {
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
   }
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);         
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename);
      }   
      if( $permissions != null) {
         chmod($filename,$permissions);
      }
   }
   function output($image_type=IMAGETYPE_JPEG) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);         
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }   
   }
   function getWidth() {
      return imagesx($this->image);
   }
   function getHeight() {
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100; 
      $this->resize($width,$height);
   }
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);

		$white = imagecolorallocate($new_image, 255, 255, 255);
		$newtransparentcolor=imagecolortransparent( $new_image, $white );
		imagefill( $new_image, 0, 0, $newtransparentcolor );

      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;   
   }      
}
?>

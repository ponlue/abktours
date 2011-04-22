<?php
	/*
	* ContactMap Component Google Map for Joomla! 1.5.x
	* Version 3.8
	* Creation date: Octobre 2010
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die('Restricted access');

$editor = &JFactory::getEditor();
$config = &JComponentHelper::getParams('com_contactmap'); 


$_lat = $this->config->get('contactmap_centre_lat');
$_lng = $this->config->get('contactmap_centre_lng');
$_zoom = $this->config->get('contactmap_zoom');
if (empty($_lat)) {$_lat = 47.927385663;};
if (empty($_lng)) {$_lng = 2.1437072753;};
if (empty($_zoom)) {$_zoom = 10;};

?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
/*		if((form.category.value)=="0"){
			messages+="<b><?php echo JText::_( 'PLEASE_SELECT_CATEGORY' );?><b><br>";
			form.category.focus();
			return;
		}
*/		// do field validation
		if ((form.name.value == "")||(form.catid.value == "0")) {
			alert( "<?php echo JText::_( 'CONTACTMAP_CHAMPS_VIDE', true ); ?>" );
		} else {
			submitform( pressbutton );
		}
	}
</script>

<script language="javascript" type="text/javascript">
    var geocoder;
    var map;
    var marker1;

    function init() {
		UpdateAddress();
		geocoder = new google.maps.Geocoder();
        
		var lat, lng, zoom_carte;
        if(document.adminForm.glat.value!=0) lat = document.adminForm.glat.value;
        else lat = <?php echo $_lat?>;
        if(document.adminForm.glng.value!=0) lng = document.adminForm.glng.value;
        else lng = <?php echo $_lng?>;
        if(document.adminForm.gzoom.value!=0) zoom_carte = parseInt(document.adminForm.gzoom.value);
        else zoom_carte = <?php echo $_zoom?>;

		var latlng = new google.maps.LatLng(lat, lng);
		var myOptions = {
		  zoom: zoom_carte,
		  center: latlng,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		};

		map = new google.maps.Map(document.getElementById("map"), myOptions);

	  google.maps.event.addListener(map, "bounds_changed", function() {
		   document.adminForm.gzoom.value = map.getZoom();
	  });

      // Create a draggable marker which will later on be binded to a
      marker1 = new google.maps.Marker({
          map: map,
          position: new google.maps.LatLng(lat, lng),
          draggable: true,
          title: 'Drag me!'
      });
	  google.maps.event.addListener(marker1, "drag", function() {
		document.adminForm.glat.value = marker1.getPosition().lat();
		document.adminForm.glng.value = marker1.getPosition().lng();
	  });
    }

    // Register an event listener to fire when the page finishes loading.
    google.maps.event.addDomListener(window, 'load', init);
 
  
    function showAddress() {
		var address = document.adminForm.localisation.value;
		if (geocoder) {
			geocoder.geocode( { 'address' : address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
				  map.setCenter(results[0].geometry.location);
				  marker1.setPosition(results[0].geometry.location); 
					document.adminForm.glat.value = results[0].geometry.location.lat();
					document.adminForm.glng.value = results[0].geometry.location.lng();
				} else {
				  alert(address + " not found for the following reason: " + status);
				}
			})
		}
    }

    function getCoordinate() {
		var lat, lng;
        if(document.adminForm.glat.value!=0) lat = document.adminForm.glat.value;
        else lat = <?php echo $_lat?>;
        if(document.adminForm.glng.value!=0) lng = document.adminForm.glng.value;
        else lng = <?php echo $_lng?>;
        if(document.adminForm.gzoom.value!=0) zoom_carte = parseInt(document.adminForm.gzoom.value);
        else zoom = <?php echo $_zoom?>;

		var latlng = new google.maps.LatLng(lat, lng);
		map.setZoom(zoom_carte);
		map.setCenter(latlng);
		marker1.setPosition(latlng); 
    }
	
	function changeDisplayImage(chemin) {
		if (document.adminForm.image.value !='') {
			document.adminForm.imagelib.src=chemin + document.adminForm.image.value;
		} else {
			document.adminForm.imagelib.src='images/blank.png';
		}

		if (document.adminForm.icon.value !='') {
			document.adminForm.imageicon.src='images/' + document.adminForm.icon.value;
		} else {
			document.adminForm.imageicon.src='images/blank.png';
		}
	}

    function addphoto(file, indice){
        var optX = new Option(file, file);
        var selX = document.forms[0].elements['image'];
        var lenghX = selX.length;
        selX.options[lenghX] = optX;
                selX.options[lenghX].selected = true;
    }

	function jSelectArticle(id, title, object) {
		document.getElementById(object + '_id').value = id;
		document.getElementById(object + '_name').value = title;
		document.getElementById('sbox-window').close();
	}

	function UpdateAddress(){
 		document.adminForm.localisation.value = document.adminForm.address.value + " " + document.adminForm.postcode.value + " " + document.adminForm.suburb.value + " " + document.adminForm.state.value + ", " + document.adminForm.country.value;	
	}

	GSearch.setOnLoadCallback(OnLoad);	

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" >
<div>
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'CONTACTMAP_DETAILS' ); ?></legend>
	<table class="admintable">
		<tr>
			<td width="110" class="key">
				<label for="title">
					<?php echo JText::_( 'CONTACTMAP_NOM' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="name" id="name" size="60" value="<?php echo str_replace('"', '&quot;',$this->contactmap->name); ?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="alias">
					<?php echo JText::_( 'Alias' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="alias" id="alias" size="32" maxlength="250" value="<?php echo $this->contactmap->alias;?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label>
					<?php echo JText::_( 'CONTACTMAP_CATEGORIE' ); ?>:
                </label>
			</td>
			<td>
				<?php
					echo $this->lists['catid'];
				?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="user_id">
					<?php echo JText::_( 'CONTACTMAP_USER_LINK' ); ?>:
				</label>
			</td>
			<td >
				<?php echo $this->lists['user_id'];?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="con_position">
					<?php echo JText::_( 'CONTACTMAP_FONCTION_CONTACT' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="con_position" id="con_position" size="60" maxlength="255" value="<?php echo $this->contactmap->con_position; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="alias">
					<?php echo JText::_( 'CONTACTMAP_ADRESSE' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="textarea" name="address" id="address" cols="34" rows="3"><?php echo str_replace('"', '&quot;',$this->contactmap->address); ?></TEXTAREA>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="lag">
					<?php echo JText::_( 'CONTACTMAP_CODEPOSTAL' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="postcode" id="postcode" size="60" value="<?php echo str_replace('"', '&quot;',$this->contactmap->postcode); ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="lag">
					<?php echo JText::_( 'CONTACTMAP_VILLE' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="suburb" id="suburb" size="60" value="<?php echo str_replace('"', '&quot;',$this->contactmap->suburb); ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="lag">
					<?php echo JText::_( 'CONTACTMAP_DEPARTEMENT' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="state" id="state" size="60" value="<?php echo str_replace('"', '&quot;',$this->contactmap->state); ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="lag">
					<?php echo JText::_( 'CONTACTMAP_PAY' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="country" id="country" size="60" value="<?php echo str_replace('"', '&quot;',$this->contactmap->country); ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="lag">
					<?php echo JText::_( 'CONTACTMAP_TEL' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="telephone" id="telephone" size="60" value="<?php echo str_replace('"', '&quot;',$this->contactmap->telephone); ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="lag">
					<?php echo JText::_( 'CONTACTMAP_TEL' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="mobile" id="mobile" size="60" value="<?php echo str_replace('"', '&quot;',$this->contactmap->mobile); ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="lag">
					<?php echo JText::_( 'CONTACTMAP_FAX' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="fax" id="fax" size="60" value="<?php echo str_replace('"', '&quot;',$this->contactmap->fax); ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="lag">
					<?php echo JText::_( 'CONTACTMAP_EMAIL' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="email_to" id="email_to" size="60" value="<?php echo str_replace('"', '&quot;',$this->contactmap->email_to); ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="lag">
					<?php echo JText::_( 'CONTACTMAP_SITE_WEB' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="webpage" id="webpage" size="60" value="<?php echo str_replace('"', '&quot;',$this->contactmap->webpage); ?>" />
			</td>
		</tr>
        <?php $adresselocalisation = "".$this->contactmap->address." ".$this->contactmap->postcode." ".$this->contactmap->suburb." ".$this->contactmap->country.""; ?>
        <tr>
            <td width="110" class="key">
            	<label for="title">       
            		<?php echo JText::_('CONTACTMAP_MAJ_ADRESSE'); ?>:
            	</label>
            </td>
            <td valign="top">
            	<input type="text" style="width:70%" name="localisation" value=<?php echo ('"'.str_replace('"', '&quot;',$adresselocalisation).'"'); ?> /><input type="button" onclick="showAddress();" value="<?php echo JText::_('CONTACTMAP_CHERCHER'); ?>" />
            </td>
		</tr>
        <tr>
            <td width="110" class="key">
            	<label for="title">
                	<?php echo JText::_('CONTACTMAP_LAT'); ?> - <?php echo JText::_('CONTACTMAP_LON'); ?> - Zoom:
              	</label>
            </td>
            <td valign="top">
                <input class="inputbox" type="text" name="glat" id="glat" size="20" value="<?php echo $this->contactmap->glat ?>" />
                <input class="inputbox" type="text" name="glng" id="glng" size="20" value="<?php echo $this->contactmap->glng ?>" />
                <input class="inputbox" type="text" name="gzoom" id="gzoom" size="20" value="<?php echo $this->contactmap->gzoom ?>" />
                <input type="button" onclick="getCoordinate();" value="<?php echo JText::_('CONTACTMAP_CHERCHER_COORDONNEES'); ?>" />
            </td>
    	</tr>
        <tr>
            <td width="100" align="right" class="key">
              	<label for="title">
              		<?php echo JText::_('CONTACTMAP_CARTE'); ?>:
              	</label>
            </td>
            <td>
            	<div id="map" style="width: 600px; height: 500px; overflow:hidden;"></div>
            </td>
        </tr>
        <tr>
            <td width="30%" class="key">
              	<label for="title">
              		<?php echo JText::_('CONTACTMAP_IMAGE'); ?>:
              	</label>
            </td>
            <td valign="center"">
            	<?php 
                    $directory		= JURI::base().'..'.'/images/stories/';
					$javascript		= 'onchange="changeDisplayImage('."'".$directory."'".');"';

					if ((stristr($this->contactmap->image,'bmp'))||(stristr($this->contactmap->image,'gif'))||(stristr($this->contactmap->image,'jpg'))||(stristr($this->contactmap->image,'jpeg'))||(stristr($this->contactmap->image,'png'))) {
						?>
						<img src="<?php echo $directory.$this->contactmap->image; ?>" name="imagelib" />
						<?php
					} else {
						?>
						<img src="images/blank.png" name="imagelib" />
						<?php
					}
					echo ('<br />');
					echo $chemin	= '/images/stories/';
					echo $lists		= JHTML::_('list.images', 'image', $this->contactmap->image, $javascript, $chemin, "bmp|gif|jpg|jpeg|png"  );
				?>
            		<br /><a style="cursor:pointer; font-size: 150%" onclick="popupWindow('index.php?option=com_contactmap&controller=contactmap&tmpl=component&task=edit_upload','win1',420,120,'no');" class="toolbar"><img src="../administrator/images/upload_f2.png" align="absmiddle" height="32" width="32" border="0" /> <?php echo JText::_('CONTACTMAP_UPLOAD') ?></a>
            </td>
     	</tr>
    	<tr>
            <td width="110" class="key">
            	<label for="title">
            	<?php echo JText::_( 'CONTACTMAP_MESSAGE' ); ?>:
            	</label>
            </td>
        	<td valign="top" class="inputbox">
            	<?php
				echo $editor->display( 'text_message', $this->contactmap->text, '100%', '300', '75', '20');
				?>
        	</td>
		</tr>
    	<tr>
            <td width="110" class="key">
            	<label for="title">
            	<?php echo JText::_( 'CONTACTMAP_HORAIRES_PRIX' ); ?>:
            	</label>
            </td>
        	<td valign="top" class="inputbox">
            	<?php
				echo $editor->display( 'text_horaires_prix', $this->contactmap->horaires_prix, '100%', '200', '75', '20', false);
				?>
        	</td>
		</tr>
		<tr>
			<td width="100" class="key">
				<label for="marker"><?php echo JText::_( 'CONTACTMAP_MARKER' ); ?>:</label>
			</td>
			<td>
				<table>
					<tr>
					<?php 
						$cnt = 0;
						foreach($this->marqueurs as $marqueur) {
							$checked = '';
							if (($this->contactmap->marqueur == $marqueur->url) || (empty($this->contactmap->marqueur) && $marqueur->id == '1')) { $checked = 'checked="checked"'; }
							echo '<td width="40" align="center" valign="top" style="border:1px solid #eeeeee"><img src="'.$marqueur->url.'" title="'.$marqueur->nom.'" /><br /><input type="radio" name="marqueur" id="marqueur" value="'.$marqueur->url.'" '.$checked.' /></td>';
							if ($cnt < 15) {
								$cnt++;
							} else {
								echo '</tr><tr>';
								$cnt = 0;
							}
						}
					?>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="120" class="key">
				<?php echo JText::_( 'CONTACTMAP_TYPE_AFFICHAGE' ); ?>:
			</td>
			<td>
            	<table>
				<?php
					$checked0 = '';
					$checked1 = '';
					$checked2 = '';
					$checked3 = '';
					switch ($this->contactmap->affichage) {
					case 0:
						$checked0='checked="checked"';
						break;
					case 1:
						$checked1='checked="checked"';
						break;
					case 2:
						$checked2='checked="checked"';
						break;
					default:
						$checked3='checked="checked"';
					}
					echo '<td width="200" align="left" ><input type="radio" name="affichage" id="affichage" value="0" '.$checked0.' />&nbsp;'.JText::_( 'CONTACTMAP_AFFICHAGE_COMPLET').'</td>';
					echo '<td width="200" align="left"><input type="radio" name="affichage" id="affichage" value="1" '.$checked1.' />&nbsp;'.JText::_( 'CONTACTMAP_AFFICHAGE_DETAILS').'</td>';
					echo '<td width="200" align="left"><input type="radio" name="affichage" id="affichage" value="2" '.$checked2.' />&nbsp;'.JText::_( 'CONTACTMAP_AFFICHAGE_MESSAGE').'</td>';
					echo '<td width="200" align="left"><input type="radio" name="affichage" id="affichage" value="3" '.$checked3.' />&nbsp;'.JText::_( 'CONTACTMAP_AFFICHAGE_TITRE').'</td>';
				 ?>
                 </table>
			</td>
		</tr>
		<tr>
			<td width="120" class="key">
				<?php echo JText::_( 'CONTACTMAP_PUBLIE' ); ?>:
			</td>
			<td >
				<?php echo JHTML::_( 'select.booleanlist',  'published', 'class="inputbox"', $this->contactmap->published ); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<label for="ordering">
					<?php echo JText::_( 'Ordering' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['ordering']; ?>
			</td>
		</tr>
		<tr>
			<td valign="top" class="key">
				<label for="access">
					<?php echo JText::_( 'Access' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['access']; ?>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="alias">
					<?php echo JText::_( 'CONTACTMAP_METADESC' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="inputbox" name="metadesc" id="metadesc" cols="70" rows="4"><?php echo $this->contactmap->metadesc; ?></textarea>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="alias">
					<?php echo JText::_( 'CONTACTMAP_METAKEY' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="inputbox" name="metakey" id="metakey" cols="70" rows="4"><?php echo $this->contactmap->metadesc; ?></textarea>
			</td>
		</tr>
		<tr>
			<td width="120" class="key">&nbsp;
				
			</td>
			<td style="text-align:left" class="key">
				<?php echo JText::_( 'CONTACTMAP_EXTERNE' ); ?>:
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="lag">
					<?php echo JText::_( 'CONTACTMAP_LINK' ); ?>:
				</label>
			</td>
			<td>
            	<div style="float: left;">
				<input class="inputbox" type="text" name="text_link" id="id_name" size="60" value="<?php echo str_replace('"', '&quot;',$this->contactmap->link); ?>" />
                </div>
                <div class="button2-left">
                	<div class="blank">
                    	<a class="modal" title="<?php echo JText::_( 'CONTACTMAP_SELECT_ARTICLE' ); ?>"  href="index.php?option=com_content&amp;task=element&amp;tmpl=component&amp;object=id" rel="{handler: 'iframe', size: {x: 650, y: 375}}">S&eacute;lection d&rsquo;un article</a>
                    </div>
                </div>
                <input type="hidden" id="id_id" name="article_id" value="<?php echo $this->contactmap->article_id; ?>" />                
			</td>
		</tr>
        <tr>
            <td width="30%" class="key">
              	<label>
              		<?php echo JText::_('CONTACTMAP_ICON'); ?>:
              	</label>
            </td>
            <td valign="center"">
            	<?php 
					if ((stristr($this->contactmap->icon,'bmp'))||(stristr($this->contactmap->icon,'gif'))||(stristr($this->contactmap->icon,'jpg'))||(stristr($this->contactmap->icon,'jpeg'))||(stristr($this->contactmap->icon,'png'))) {
						?>
						<img src="<?php echo "images/".$this->contactmap->icon; ?>" name="imageicon" />
						<?php
					} else {
						?>
						<img src="images/blank.png" name="imageicon" />
						<?php
					}
					echo ('<br />');
					echo "/administrator/images/";
					echo $lists		= JHTML::_('list.images', 'icon', $this->contactmap->icon, $javascript, "/administrator/images", "f2.bmp|f2.gif|f2.jpg|f2.jpeg|f2.png"  );
				?>
            </td>
     	</tr>
		<tr>
			<td class="key">
				<label>
					<?php echo JText::_( 'CONTACTMAP_ICON_LABEL' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="icon_label" id="icon_label" size="60" value="<?php echo str_replace('"', '&quot;',$this->contactmap->icon_label); ?>" />
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_contactmap" />
<input type="hidden" name="id" value="<?php echo $this->contactmap->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="contactmap" />
</form>
<div class="copyright" align="center">
	<br />
	<?php echo JText::_( 'CONTACTMAP_COPYRIGHT' );?>
</div>

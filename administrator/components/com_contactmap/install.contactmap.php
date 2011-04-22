<?php
    /*
    * ContactMap Component Google Map for Joomla! 1.5.x
    * Version 3.13
    * Creation date: Mars 2011
    * Author: Fabrice4821 - www.gmapfp.org
    * Author email: webmaster@gmapfp.org
    * License GNU/GPL
    */

    defined('_JEXEC') or die('Restricted access');


    function com_install() {

		$version_num='3';
		$indice_num='13';
// version non compatible       
        $version_num_comp='1.0';

        global $mainframe;

        $file = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_contactmap'.DS.'contactmap.xml';
		$version_no = '';
        if(file_exists($file)) {
            $xml = new JSimpleXML;
            $xml->loadFile($file);
            $xml = $xml->document;
            $version_no = @$xml->version[0]->data();
        }
        if ($version_no){
			$version = explode('.',$version_no);
			if((($version[0] == $version_num)and($version[1] == $indice_num))or($version_no == $version_num_comp))
            {
               $message = "Vous avez d&eacute;j&agrave; la version ".$version_num.'.'.$indice_num." de ContactMap ou une version incompatbile (V1.0 =&gt; d&eacute;sinstall&eacute; l&aacute;) "."</li><li>You already have ContactMap v".$version_num." or a incompatible version (V1.0 =&gt; uninstall it)";
                JError::raiseWarning(100, $message);
                $mainframe->redirect('index.php?option=com_installer');
            }else{
                upgrade($version_num.'.'.$indice_num);
            };
        }else{
            new_install($version_num.'.'.$indice_num);
        };
    }

function new_install($version_num){

        //Installation du fichier CSS
        $filesource = JPATH_SITE .DS.'components'.DS.'com_contactmap'.DS.'views'.DS.'contactmap'.DS.'contactmap3.css';
        $filedest = JPATH_SITE .DS.'components'.DS.'com_contactmap'.DS.'views'.DS.'contactmap'.DS.'contactmap.css';
        JFile::copy($filesource, $filedest,null);

        @mail('fayauxlogescpa@gmail.com','ContactMap v'.$version_num.'.'.$indice_num.' nouvelle install.',$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'],'From:fayauxlogescpa@gmail.com');

        $db =& JFactory::getDBO();
        global $mainframe;
        jimport('joomla.filesystem.folder');

/**************************************************/
// Ajout des éléments à la table contact_détails  //
/**************************************************/
        $query = "SHOW COLUMNS FROM `#__contact_details` LIKE 'message'";
        $db->setQuery( $query );
        $list_id = $db->loadObject();
        if (empty($list_id)) {
            $query = "ALTER TABLE  `#__contact_details` ADD  `message` MEDIUMTEXT NULL DEFAULT NULL AFTER  `misc` ;";
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                exit($db->stderr());
            }
        };

        $query = "SHOW COLUMNS FROM `#__contact_details` LIKE 'horaires_prix'";
        $db->setQuery( $query );
        $list_id = $db->loadObject();
        if (empty($list_id)) {
            $query = "ALTER TABLE  `#__contact_details` ADD  `horaires_prix` MEDIUMTEXT NULL DEFAULT NULL AFTER  `message` ;";
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                exit($db->stderr());
            }
        };

        $query = "SHOW COLUMNS FROM `#__contact_details` LIKE 'link'";
        $db->setQuery( $query );
        $list_id = $db->loadObject();
        if (empty($list_id)) {
            $query = "ALTER TABLE  `#__contact_details` ADD  `link` VARCHAR(200) NULL DEFAULT NULL AFTER  `horaires_prix` ;";
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                exit($db->stderr());
            }
        };

        $query = "SHOW COLUMNS FROM `#__contact_details` LIKE 'article_id'";
        $db->setQuery( $query );
        $list_id = $db->loadObject();
        if (empty($list_id)) {
            $query = "ALTER TABLE  `#__contact_details` ADD  `article_id` INT(100) NOT NULL DEFAULT '0' AFTER  `link` ;";
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                exit($db->stderr());
            }
        };

        $query = "SHOW COLUMNS FROM `#__contact_details` LIKE 'icon'";
        $db->setQuery( $query );
        $list_id = $db->loadObject();
        if (empty($list_id)) {
            $query = "ALTER TABLE  `#__contact_details` ADD  `icon` VARCHAR(100) NULL DEFAULT NULL AFTER  `article_id` ;";
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                exit($db->stderr());
            }
        };

        $query = "SHOW COLUMNS FROM `#__contact_details` LIKE 'icon_label'";
        $db->setQuery( $query );
        $list_id = $db->loadObject();
        if (empty($list_id)) {
            $query = "ALTER TABLE  `#__contact_details` ADD  `icon_label` VARCHAR(100) NULL DEFAULT NULL AFTER  `icon` ;";
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                exit($db->stderr());
            }
        };

        $query = "SHOW COLUMNS FROM `#__contact_details` LIKE 'affichage'";
        $db->setQuery( $query );
        $list_id = $db->loadObject();
        if (empty($list_id)) {
            $query = "ALTER TABLE  `#__contact_details` ADD  `affichage` SMALLINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `icon_label` ;";
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                exit($db->stderr());
            }
        };

        $query = "SHOW COLUMNS FROM `#__contact_details` LIKE 'marqueur'";
        $db->setQuery( $query );
        $list_id = $db->loadObject();
        if (empty($list_id)) {
            $query = "ALTER TABLE  `#__contact_details` ADD  `marqueur` VARCHAR(200) NULL DEFAULT NULL AFTER  `affichage` ;";
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                exit($db->stderr());
            }
        };

        $query = "SHOW COLUMNS FROM `#__contact_details` LIKE 'glng'";
        $db->setQuery( $query );
        $list_id = $db->loadObject();
        if (empty($list_id)) {
            $query = "ALTER TABLE  `#__contact_details` ADD  `glng` VARCHAR(12) NULL DEFAULT NULL AFTER  `marqueur` ;";
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                exit($db->stderr());
            }
        };

        $query = "SHOW COLUMNS FROM `#__contact_details` LIKE 'glat'";
        $db->setQuery( $query );
        $list_id = $db->loadObject();
        if (empty($list_id)) {
            $query = "ALTER TABLE  `#__contact_details` ADD  `glat` VARCHAR(12) NULL DEFAULT NULL AFTER  `glng` ;";
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                exit($db->stderr());
            }
        };

        $query = "SHOW COLUMNS FROM `#__contact_details` LIKE 'gzoom'";
        $db->setQuery( $query );
        $list_id = $db->loadObject();
        if (empty($list_id)) {
            $query = "ALTER TABLE  `#__contact_details` ADD  `gzoom` VARCHAR(2) NULL DEFAULT NULL AFTER  `glat` ;";
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                exit($db->stderr());
            }
        };

        $query = "SHOW COLUMNS FROM `#__contact_details` LIKE 'metadesc'";
        $db->setQuery( $query );
        $list_id = $db->loadObject();
        if (empty($list_id)) {
            $query = "ALTER TABLE  `#__contact_details` ADD  `metadesc` TEXT NULL DEFAULT NULL AFTER  `gzoom` ;";
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                exit($db->stderr());
            }
        };

        $query = "SHOW COLUMNS FROM `#__contact_details` LIKE 'metakey'";
        $db->setQuery( $query );
        $list_id = $db->loadObject();
        if (empty($list_id)) {
            $query = "ALTER TABLE  `#__contact_details` ADD  `metakey` TEXT NULL DEFAULT NULL AFTER  `metadesc` ;";
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum()) {
                exit($db->stderr());
            }
        };

/*mise à jour des données marqueurs*/
        $query = "INSERT INTO `#__contactmap_marqueurs` VALUES
('', 'marqueur', 'http://www.google.com/mapfiles/marker.png',1),
('', 'marqueur home', 'http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin_star|home|FFFF00|FF0000',1),
('', 'marqueur flag', 'http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin_star|flag|FFFF00|FF0000',1),
('', 'marqueur info', 'http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin_star|info|FFFF00|FF0000',1),
('', 'marqueur bar', 'http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin_star|bar|FFFF00|FF0000',1),
('', 'marqueur cafe', 'http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin_star|cafe|FFFF00|FF0000',1),
('', 'marqueur perso', 'http://chart.apis.google.com/chart?chst=d_map_spin&chld=1.2|0|FF0000|10|_|foo|bar',1),
('', 'marqueurA', 'http://www.google.com/mapfiles/markerA.png',1),
('', 'marqueurB', 'http://www.google.com/mapfiles/markerB.png',1),
('', 'marqueurC', 'http://www.google.com/mapfiles/markerC.png',1),
('', 'marqueurD', 'http://www.google.com/mapfiles/markerD.png',1),
('', 'marqueurE', 'http://www.google.com/mapfiles/markerE.png',1),
('', 'marqueurF', 'http://www.google.com/mapfiles/markerF.png',1),
('', 'marqueurG', 'http://www.google.com/mapfiles/markerG.png',1),
('', 'marqueurH', 'http://www.google.com/mapfiles/markerH.png',1),
('', 'marqueurI', 'http://www.google.com/mapfiles/markerI.png',1),
('', 'marqueurJ', 'http://www.google.com/mapfiles/markerJ.png',1),
('', 'marqueurK', 'http://www.google.com/mapfiles/markerK.png',1),
('', 'marqueurL', 'http://www.google.com/mapfiles/markerL.png',1),
('', 'marqueurM', 'http://www.google.com/mapfiles/markerM.png',1),
('', 'marqueurN', 'http://www.google.com/mapfiles/markerN.png',1),
('', 'marqueurO', 'http://www.google.com/mapfiles/markerO.png',1),
('', 'marqueurP', 'http://www.google.com/mapfiles/markerP.png',1),
('', 'marqueurQ', 'http://www.google.com/mapfiles/markerQ.png',1),
('', 'marqueurR', 'http://www.google.com/mapfiles/markerR.png',1),
('', 'marqueurS', 'http://www.google.com/mapfiles/markerS.png',1),
('', 'marqueurT', 'http://www.google.com/mapfiles/markerT.png',1),
('', 'marqueurU', 'http://www.google.com/mapfiles/markerU.png',1),
('', 'marqueurV', 'http://www.google.com/mapfiles/markerV.png',1),
('', 'marqueurW', 'http://www.google.com/mapfiles/markerW.png',1),
('', 'marqueurX', 'http://www.google.com/mapfiles/markerX.png',1),
('', 'marqueurY', 'http://www.google.com/mapfiles/markerY.png',1),
('', 'marqueurZ', 'http://www.google.com/mapfiles/markerZ.png',1),
('', 'marqueurBleu', 'http://maps.gstatic.com/intl/fr_ALL/mapfiles/ms/micons/blue-dot.png',1),
('', 'marqueurVert', 'http://maps.gstatic.com/intl/fr_ALL/mapfiles/ms/micons/green-dot.png',1),
('', 'marqueurOrange', 'http://maps.gstatic.com/intl/fr_ALL/mapfiles/ms/micons/orange-dot.png',1),
('', 'marqueurJaune', 'http://maps.gstatic.com/intl/fr_ALL/mapfiles/ms/micons/yellow-dot.png',1),
('', 'marqueurViolet', 'http://maps.gstatic.com/intl/fr_ALL/mapfiles/ms/micons/purple-dot.png',1),
('', 'marqueurRose','http://maps.gstatic.com/intl/fr_ALL/mapfiles/ms/micons/pink-dot.png',1),
('', 'purple', 'http://labs.google.com/ridefinder/images/mm_20_purple.png',1),
('', 'yellow', 'http://labs.google.com/ridefinder/images/mm_20_yellow.png',1),
('', 'blue', 'http://labs.google.com/ridefinder/images/mm_20_blue.png',1),
('', 'white', 'http://labs.google.com/ridefinder/images/mm_20_white.png',1),
('', 'green', 'http://labs.google.com/ridefinder/images/mm_20_green.png',1),
('', 'red', 'http://labs.google.com/ridefinder/images/mm_20_red.png',1),
('', 'black', 'http://labs.google.com/ridefinder/images/mm_20_black.png',1),
('', 'orange', 'http://labs.google.com/ridefinder/images/mm_20_orange.png',1),
('', 'gray', 'http://labs.google.com/ridefinder/images/mm_20_gray.png',1),
('', 'brown', 'http://labs.google.com/ridefinder/images/mm_20_brown.png',1);";
        $db->setQuery($query);
        $db->query();
        if ($db->getErrorNum()) {
            exit($db->stderr());
        }

/*mise à jour des paramètres par défaut*/
        $query = "UPDATE #__components SET params='contactmap_height=500
contactmap_width=500
contactmap_auto=1
contactmap_centre_lat=47.927644470
contactmap_centre_lng=2.1391367912
contactmap_zoom=5
contactmap_zoom_lightbox_carte=0
contactmap_zoom_lightbox_imprimer=0
contactmap_width_bulle_contactmap=400
contactmap_taille_bulle_cesure=200
contactmap_itineraire=1
contactmap_traffic=1
contactmap_streetView=1
contactmap_height_sv=500
contactmap_photo_icon=0
contactmap_normal=1
contactmap_satellite=1
contactmap_hybrid=1
contactmap_physic=1
contactmap_vertical=1
contactmap_choix_affichage_carte=1
contactmap_mapcontrol=1
contactmap_scalecontrol=1
contactmap_mousewheel=1
contactmap_afficher_horaires_prix=1
contactmap_hauteur_img=100
contactmap_afficher_captcha=1
contactmap_geoXML=
contactmap_licence=1' WHERE name='ContactMap'";
        $db->setQuery($query);
        $db->query();
        if ($db->getErrorNum()) {
            exit($db->stderr());
        }
        affiche_bienvenue(1,$version_num);
}


    function upgrade($version_num) {

        @mail('fayauxlogescpa@gmail.com','ContactMap v'.$version_num.' update.',$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'],'From:fayauxlogescpa@gmail.com');

        $db =& JFactory::getDBO();

        affiche_bienvenue(2,$version_num);
    }



function affiche_bienvenue($install,$version_num) {
if ($install == 1) {
echo "<h1>ContactMap Installation</h1>";
}else{
echo "<h1>ContactMap Mise &agrave; jour</h1>";
};
?>
<p>Bienvenue sur ContactMap v<?php echo $version_num?> !<br/>
Avant de commencer, je vous invite, si ce n'est pas d&eacute;j&agrave; fait, &agrave; d&eacute;couvrir toutes les possibilit&eacute;s de se composant et de son ou ses plugins sur son <a target="_blank" href="http://www.gmapfp.francejoomla.net/index.php/fr">Site officiel</a>.<br />
Vous pourrez y <a target="_blank" href="http://www.gmapfp.francejoomla.net/index.php/fr/telechargement">t&eacute;l&eacute;charger</a> les mise &agrave; jours et consulter le <a target="_blank" href="http://www.gmapfp.francejoomla.net/index.php/fr/forum"> forum</a>.</p>
<p>Au revoir, et bonne continuation avec ContactMap</p>
<br />
<br />
<br />
<?php
if ($install == 1) {
echo "<h1>ContactMap Installation (in English)</h1>";
}else{
echo "<h1>ContactMap Upgrade (in English)</h1>";
};
?>
<p>Welcome on v<?php echo $version_num?> ContactMap !<br/>
Before starting, I invite you, if this isn't already made, to discovery all the possibilities of this component and thisd plugin on its <a target="_blank" href="http://www.gmapfp.francejoomla.net/index.php/en">Official Site</a>.<br />
You will be able there to <a target="_blank" href="http://www.gmapfp.francejoomla.net/index.php/en/download">download</a> the update and consult the <a target="_blank" href="http://www.gmapfp.francejoomla.net/index.php/en/forum"> forum</a>.</p>
<p>Goodbye, and good continuation with ContactMap</p>
<?php
        }
?>

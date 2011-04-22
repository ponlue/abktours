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

$Lig_Col=$this->params->get('affichage_colonne', 1);

if ($this->params->get('show_page_title', 1)) : ?>
<div class="componentheading<?php echo $this->params->get('pageclass_sfx');?>">
    <?php echo $this->escape($this->params->get('page_title')); ?>
</div>
<?php endif; 

$document   =& JFactory::getDocument();

//foreach ($this->rows as $row) {
$row = $this->rows[0];

    if ($row->metadesc) {
        $document->setDescription( $row->metadesc );
    }
    if ($row->metakey) {
        $document->setMetadata('keywords', $row->metakey);
    }
    if ($mainframe->getCfg('MetaTitle') == '1') {
        $mainframe->addMetaTag('title', @$row->suburb.' : '.$row->name.' ('.$row->category_name.')');
    }
    $recipient = $row->email_to;

    if ($row->image!=null) {
        $image=JURI::base().'images/stories/'.$row->image;
    };
    $clock=JURI::base().'components/com_contactmap/images/clock_32.png';
    $printer=JURI::base().'components/com_contactmap/images/printer.png';

?>
    <div id="enregistrement">
    	<div>
            <div style="float:right">
            <?php 
			if (JRequest::getVar('flag', 0, '0', 'int')==0) {
                if (($row->horaires_prix!=null)&&($this->params->get('contactmap_afficher_horaires_prix')==1)) {
                    $link =JURI::base().'index.php?option=com_contactmap&view=contactmap&tmpl=component&layout=horaires_item&flag=1&id='.$row->id.'&Itemid='.JRequest::getVar('Itemid', 0, '', 'int') ?>
                    <a class='lightboxgmafp' rev="width:550 height:400 disableScroll:true" href="<?php echo $link ?>"  title="<?php echo JText::_('CONTACTMAP_HORAIRES_PRIX');?>"><img src=<?php echo $clock; ?> /></a>&nbsp;&nbsp;&nbsp; 
				<?php 
				};
				$link = JURI::base().'index.php?option=com_contactmap&view=contactmap&tmpl=component&layout=item_carte&flag=1&id='.$row->id.'&Itemid='.JRequest::getVar('Itemid', 0, '', 'int');
				$width_carte='100%';
				if ($this->params->get('contactmap_itineraire')==1) {
					$height_carte=$this->params->get('contactmap_height')+170;
				}else{
					$height_carte=$this->params->get('contactmap_height')+45;
				};
				$link =JURI::base().'index.php?option=com_contactmap&view=contactmap&tmpl=component&layout=print_article&flag=1&id='.$row->id; ?>
				<a href="<?php echo $link ?>" class="lightboxgmafp" rev="showPrint:true width:<?php echo $width_carte; ?> height:92% disableScroll:true controlsPos:br" title="<?php echo JText::_('CONTACTMAP_IMPRIMER');?>"><img src= <?php echo $printer; ?>  /></a>&nbsp;&nbsp;&nbsp;
			<?php 
			};?>
					<?php
                    $link4=substr($row->link,0,4);
                    $link5=substr($row->link,0,5);
                    $link9=substr($row->link,0,9);
                    $link10=substr($row->link,0,10);
                    $linkok=0;                      
                    if ((!empty($row->icon))||(!$row->icon='')) {
                        if ($row->article_id>=1) {
                            $linkmap=JURI::base()."index.php?option=com_content&view=article&tmpl=component&id=".$row->article_id."&Itemid=".JRequest::getVar('Itemid', 0, '', 'int');
                            $linkok=1;
                        };
                        if (($link5=="http:")||($link4=="www.")||($link9=="index.php")) {
                            $linkmap=$row->link;
                            if ($link4=="www.") {$linkmap="http://".$linkmap;};
                            if ($link10=="index.php?") {$linkmap=JURI::base().$linkmap."&tmpl=component";};
                            $linkok=1;
                        };
                    $icon=JURI::base().'administrator/images/'.$row->icon;
                    };
                    if (($linkok)&&($row->icon<>'')) { ?>
                        <a href="<?php echo $linkmap ?>" class="lightboxgmafp" rev="width:80% height:80% disableScroll:true controlsPos:br" title="<?php echo $row->icon_label;?>"><img src= <?php echo $icon; ?>  /></a>&nbsp;&nbsp;&nbsp;
                    <?php }; ?>
        	</div>
            <div>
                <h2><?php echo $row->name; ?></h2>
                <br />
                <h4><?php echo $row->con_position; ?></h4>
            </div>
		</div>
        <table class="contactmap_detail">
            <tr>
                <td class="contactmap_taille1">
                    <?php
                        if ($row->image!=null) { ?> <a class="lightboxgmafp" href='<?php echo $image; ?>'><img src=<?php echo $image; ?> height="<?php echo $this->params->get('contactmap_hauteur_img')?>"/></a>
                    <?php }; ?>
                <?php
                    if ($Lig_Col) {
                        echo '</td>';
                        echo '<td class="contactmap_taille2">';
                    }

            if ($row->address!=null) {
                $adresse = $row->address;
                $order  = array("\r\n", "\n", "\r");
                $adresse    = str_replace( $order, '<br />', $adresse );
                 if ($this->params->get('show_adresse', 1)) echo JText::_('CONTACTMAP_ADRESSE');?><span><?php echo $adresse;?></span><br /> <?php };?> 
            <?php if ($row->postcode!=null) { if ($this->params->get('show_cp', 1)) echo JText::_('CONTACTMAP_CODEPOSTAL');?><span><?php echo $row->postcode;?></span><br /> <?php };?> 
            <?php if ($row->suburb!=null) { if ($this->params->get('show_city', 1)) echo JText::_('CONTACTMAP_VILLE'); ?><span><?php  echo $row->suburb;?></span><br /> <?php };?> 
            <?php if ($row->state!=null) { if ($this->params->get('show_state', 1)) echo JText::_('CONTACTMAP_DEPARTEMENT');?><span><?php echo $row->state;?></span><br /> <?php };?> 
            <?php if ($row->country!=null) { if ($this->params->get('show_conutry', 1)) echo JText::_('CONTACTMAP_PAY');?><span><?php echo $row->country;?></span><br /> <?php };?>
            <?php
                    if ($Lig_Col) {
                        echo '</td>';
                        echo '<td>';
                    }
            ?>
            <?php if ($row->telephone!=null) { if ($this->params->get('show_phone', 1)) echo JText::_('CONTACTMAP_TEL');?><span><?php echo $row->telephone;?></span><br /> <?php };?>
            <?php if ($row->mobile!=null) { if ($this->params->get('show_phone', 1)) echo JText::_('CONTACTMAP_TEL');?><span><?php echo $row->mobile;?></span><br /> <?php };?> 
            <?php if ($row->fax!=null) { if ($this->params->get('show_fax', 1)) echo JText::_('CONTACTMAP_FAX');?><span><?php echo $row->fax;?></span><br /> <?php };?> 
            <?php if (($row->email_to!=null) and ($this->params->get('show_email_field', 1))) { if ($this->params->get('show_email', 1)) echo JText::_('CONTACTMAP_EMAIL');?><span><?php echo @JHTML::_('email.cloak',$row->email_to);?></span><br /> <?php };?> 
            <?php if ($row->webpage!=null) {
                if (substr($row->webpage,0,5)!="http:") {$lien_web = "http://".$row->webpage;} else {$lien_web = $row->webpage;};
                 if ($this->params->get('show_web', 1)) echo JText::_('CONTACTMAP_SITE_WEB');?> <span><a href= <?php echo $lien_web;?> target="_blank" > <?php echo $row->webpage;?> </a></span> <br /> <?php };?> 
                </td>
            </tr>
        </table>
        <table class="contactmap_message">
            <tr>
                <td>
                    <span><?php echo $row->misc; echo $row->message; ?></span>
                 <br /> 
                 <br />
                </td>
            </tr>
        </table>
        <?php echo $this->loadTemplate('form'); ?>

    <div>
        <?php
            if (!JRequest::getVar('flag', "", '', 'str')) {
                echo $this->map;
            }
        ?>
    </div>
<?php if ($this->params->get('contactmap_licence')) : ?>
<table width="100%">
    <tr>
        <td valign="top" align="center">
            <?php echo '<br />'.JText::_('CONTACTMAP_COPYRIGHT'); ?>
        </td>
    </tr>
</table>
<?php endif; ?>
</div>

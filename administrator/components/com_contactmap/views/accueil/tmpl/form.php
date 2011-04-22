<?php
    /*
    * ContactMap Component Google Map for Joomla! 1.5.x
    * Version 3.2
    * Creation date: Juin 2010
    * Author: Fabrice4821 - www.gmapfp.org
    * Author email: fayauxlogescpa@gmail.com
    * License GNU/GPL
    */

defined('_JEXEC') or die('Restricted access');
JHTML::_( 'behavior .modal' );

global $mainframe;
$lang       =& JFactory::getLanguage();
$template   = $mainframe->getTemplate();

$langue     =substr((@$lang->_metadata[tag]),0,2);
if ($langue!='fr') $langue!='en';

$user   = & JFactory::getUser();
?>

<table class="admintable">
    <tr>
        <td width="55%" valign="top" colspan="2">
            <div id="cpanel">
                <?php if ($user->get('gid')==25) {?>
                <div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
                    <div class="icon">
                        <a class="modal" href="index.php?option=com_config&controller=component&component=com_contactmap&path=" rel="{handler:'iframe',size: {x:570,y:620}}">
                            <?php echo JHTML::_('image.site',  'icon-48-config.png', '/templates/'. $template .'/images/header/'); ?>
                            <span><?php echo JText::_('CONTACTMAP_PARAMETRES'); ?></span>
                        </a>
                    </div>
                </div>
                <?php };?>
                <div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
                    <div class="icon">
                        <a href="index.php?option=com_contactmap&controller=contactmap&task=view">
                            <?php echo JHTML::_('image.site',  'icon-48-user.png', '/templates/'. $template .'/images/header/'); ?>
                            <span><?php echo JText::_('CONTACTMAP_LIEUX'); ?></span>
                        </a>
                    </div>
                </div>
                <div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
                    <div class="icon">
                        <a href="index.php?option=com_categories&section=com_contact_details">
                            <?php echo JHTML::_('image.site',  'icon-48-category.png', '/templates/'. $template .'/images/header/'); ?>
                            <span><?php echo JText::_('CONTACTMAP_CATEGORIES'); ?></span>
                        </a>
                    </div>
                </div>
                <div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
                    <div class="icon">
                        <a href="index.php?option=com_contactmap&controller=marqueurs&task=view">
                            <?php echo JHTML::_('image.site',  'icon-48-checkin.png', '/templates/'. $template .'/images/header/'); ?>
                            <span><?php echo JText::_('CONTACTMAP_MARQUEURS'); ?></span>
                        </a>
                    </div>
                </div>
                <div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
                    <div class="icon">
                        <a href="index.php?option=com_contactmap&controller=css&task=view">
                            <?php echo JHTML::_('image.site',  'icon-48-themes.png', '/templates/'. $template .'/images/header/'); ?>
                            <span>CSS</span>
                        </a>
                    </div>
                </div>
                <div>
                	<p>
					<script type="text/javascript">
                        <!--
						google_ad_client = "pub-0014544965086912";
						/* 728x90, date de création 03/07/10 */
						google_ad_slot = "3604075384";
						google_ad_width = 108;
						google_ad_height = 97;                        //-->
                    </script>
                    <script type="text/javascript"
                        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                    </script>
                    </p>
                </div>
            </div>
        </td>
        <td width="45%" valign="top">
        <div style="width: 100%">
        <?php
            $tabs   = $this->get('publishedTabs');
            $onglet     =&JPane::getInstance('sliders');
            echo $onglet->startPane("content-pane");
    
            foreach ($tabs as $tab) {
                $title = JText::_($tab->title);
                echo $onglet->startPanel( $title, 'contactmapcpanel-panel-'.$tab->name );
                $contenu = 'infos_' .$tab->name;
                echo $this->$contenu();
                echo $onglet->endPanel();
            }
    
            echo $onglet->endPane();

         ?>
        </div>
        </td>
    </tr>
    <tr>
        <td>
            <table class="admintable">
                <tr>
                    <td class="key">
                        <?php echo JText::_( 'Forum' );?>
                    </td>
                    <td>
                        <a href="http://www.gmapfp.org/<?php echo $langue; ?>/forum/view-topiclist/forum-7-divers" target="_new">http://www.gmapfp.org/<?php echo $langue; ?>/forum</a>
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <?php echo JText::_( 'CONTACTMAP_DOCUMENTATION' );?>
                    </td>
                    <td>
                        <a href="http://www.gmapfp.org/<?php echo $langue; ?>/documentation" target="_new">http://www.gmapfp.org/<?php echo $langue; ?>/documentation</a>
                    </td>
                </tr>
            </table>
        </td>
   </tr>
</table>
<div class="copyright" align="center">
    <br />
    <?php echo JText::_( 'CONTACTMAP_COPYRIGHT' );?>
</div>
<div class="clr"></div>


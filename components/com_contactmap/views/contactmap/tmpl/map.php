<?php
    /*
    * ContactMap Component Google Map for Joomla! 1.5.x
    * Version 3.7
    * Creation date: Septembre 2010
    * Author: Fabrice4821 - www.gmapfp.org
    * Author email: webmaster@gmapfp.org
    * License GNU/GPL
    */

defined('_JEXEC') or die('Restricted access'); 

$height_msg = '500px';
$width_msg = '400px';

?>
<?php if ($this->params->get('show_page_title', 1)) : ?>
<div class="componentheading<?php echo $this->params->get('pageclass_sfx');?>">
    <?php echo $this->escape($this->params->get('page_title')); ?>
</div>
<?php endif; 
if ($this->params->get('gmapfp_filtre')==1) :
$itemid = JRequest::getVar('Itemid', 0, '', 'int');
$perso = JRequest::getVar('id_perso', 0, '', 'int');
?>
<form action="<?php echo JRoute::_('index.php?option=com_gmapfp&view=gmapfp&id_perso='.$perso.'&Itemid='.$itemid); ?>" method="post" name="adminForm">
    <table  class="gmapfpform">
        <tr>
            <td width="60%">
                <?php echo JText::_( 'GMAPFP_FILTER' ); ?>:
                <input type="text" size="20" name="search_gmapfp" id="search_gmapfp" value="<?php echo $this->lists['search_gmapfp'];?>" class="text" onchange="document.adminForm.submit();"/>
                <button onclick="this.form.submit();"><?php echo JText::_( 'GMAPFP_GO_FILTER' ); ?></button>
                <button onclick="
                    document.getElementById('search_gmapfp').value='';
                    <?php if (@$this->lists['ville']) {?>document.adminForm.filtreville.value='-- <?php echo JText::_( 'GMAPFP_VILLE_FILTRE' ) ?> --'; <?php };?>
                    <?php if (@$this->lists['departement']) {?>document.adminForm.filtredepartement.value='-- <?php echo JText::_( 'GMAPFP_DEPARTEMENT_FILTRE' ) ?> --'; <?php };?>
                    <?php if (@$this->lists['pays']) {?>document.adminForm.filtrepays.value='-- <?php echo JText::_( 'GMAPFP_PAYS_FILTRE' ) ?> --'; <?php };?>
                    <?php if (@$this->lists['categorie']) {?>document.adminForm.filtrecategorie.value='-- <?php echo JText::_( 'GMAPFP_CATEGORIE_FILTRE' ) ?> --'; <?php };?>
                    this.form.submit();
                "><?php echo JText::_( 'GMAPFP_RESET' ); ?>
                </button>
            </td>
            <td width="40%">
                <?php
                if (@$this->lists['ville']) {echo $this->lists['ville'].'<br />';};
                if (@$this->lists['departement']) {echo $this->lists['departement'].'<br />';};
                if (@$this->lists['pays']) {echo $this->lists['pays'].'<br />';};
                if (@$this->lists['categorie']) {echo $this->lists['categorie'].'<br />';};
                ?>
                <br />
            </td>
        </tr>
    </table>
</form>
<?php endif; ?>
<div style="overflow: auto;">
<?php echo $this->map; ?>
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

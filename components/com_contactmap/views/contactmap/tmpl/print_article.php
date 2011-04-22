<?php 
	/*
	* ContactMap Component Google Map for Joomla! 1.5.x
	* Version 3.5
	* Creation date: Juillet 2010
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: fayauxlogescpa@gmail.com
	* License GNU/GPL
	*/

defined('_JEXEC') or die('Restricted access'); 
$mainframe->addCustomHeadTag( '<link rel="stylesheet" href="components/com_contactmap/views/contactmap/contactmap_print.css" media="print" type="text/css" />'); 
$mainframe->addCustomHeadTag( '<meta name="ROBOTS" content="NOINDEX,NOFOLLOW"/>'); 
JHTML::_( 'behavior .modal' );
$config =& JComponentHelper::getParams('com_contactmap'); 
$printer=JURI::base().'components/com_contactmap/images/printer.jpg';
?>
<div  id="contactmap_print">
<?php
foreach ($this->rows as $row) {	?>
    	<h1><?php echo $row->name; ?></h1><br />
        <h4><?php echo $row->con_position; ?></h4>
		<table>
    		<tr>
                <td>
                    <?php
						if ($row->image!=null) { $image=JURI::base().'/images/stories/'.$row->image;?> <img src=<?php echo $image; ?> > <?php }; ?>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="contactmap_print_taille2">
            <?php if ($row->address!=null) {echo JText::_('CONTACTMAP_ADRESSE');?><span><?php echo $row->address;?></span><br /> <?php };?> 
            <?php if ($row->postcode!=null) {echo JText::_('CONTACTMAP_CODEPOSTAL');?><span><?php echo $row->postcode;?></span><br /> <?php };?> 
            <?php if ($row->suburb!=null) {echo JText::_('CONTACTMAP_VILLE'); ?><span><?php  echo $row->suburb;?></span><br /> <?php };?> 
            <?php if ($row->state!=null) {echo JText::_('CONTACTMAP_DEPARTEMENT');?><span><?php echo $row->state;?></span><br /> <?php };?> 
            <?php if ($row->country!=null) {echo JText::_('CONTACTMAP_PAY');?><span><?php echo $row->country;?></span><br /> <?php };?> 
                </td>
                <td>
            <?php if ($row->telephone!=null) {echo JText::_('CONTACTMAP_TEL');?><span><?php echo $row->telephone;?></span><br /> <?php };?> 
            <?php if ($row->mobile!=null) {echo JText::_('CONTACTMAP_TEL');?><span><?php echo $row->mobile;?></span><br /> <?php };?> 
            <?php if ($row->fax!=null) {echo JText::_('CONTACTMAP_FAX');?><span><?php echo $row->fax;?></span><br /> <?php };?> 
            <?php if ($row->email_to!=null) {echo JText::_('CONTACTMAP_EMAIL');?><span><?php echo $row->email_to;?></span><br /> <?php };?> 
            <?php if ($row->webpage!=null) {?><label><?php echo JText::_('CONTACTMAP_SITE_WEB');?> </label><span><?php echo $row->webpage;?></span> <br /> <?php };?> <br />
                </td>
			</tr>
		</table>
        <br />
        <span><?php echo $row->misc; echo $row->message; ?></span><br /><br />
        <?php if ($row->horaires_prix!=null) {?><label><?php echo JText::_('CONTACTMAP_HORAIRES_PRIX');?> </label><br /><span><?php echo $row->horaires_prix;?></span><br /> <?php };
}; ?>
<div style="overflow: auto;">
	<?php echo $this->map; ?>
</div>
<?php if ($this->params->get('contactmap_licence')) : ?>
<table>
    <tr>
        <td valign="top" align="center">
            <?php echo '<br />'.JText::_('CONTACTMAP_COPYRIGHT'); ?>
        </td>
    </tr>
</table>
<?php endif; ?>
</div>

<?php
    /*
    * GMapFP Component Google Map for Joomla! 1.5.x
    * Version 1.0
    * Creation date: Aout 2009
    * Author: Fabrice4821 - www.gmapfp.francejoomla.net
    * Author email: fayauxlogescpa@gmail.com
    * License GNU/GPL
    */

defined('_JEXEC') or die('Restricted access'); ?>

<script language="javascript" type="text/javascript">
    function submitbutton(pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'cancel') {
            submitform( pressbutton );
            return;
        }
        // do field validation
        if (form.nom.value == "") {
            alert( "<?php echo JText::_( 'CONTACTMAP_NOM_NON_VIDE', true ); ?>" );
        } else {
            submitform( pressbutton );
        }
    }
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
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
                <input class="inputbox" type="text" name="nom" id="nom" size="60" value="<?php echo $this->marqueurs->nom; ?>" />
            </td>
        </tr>
        <tr>
            <td class="key">
                <label for="lag">
                    <?php echo JText::_( 'CONTACTMAP_URL' ); ?>:
                </label>
            </td>
            <td>
                <input class="inputbox" type="text" name="url" id="url" size="60" value="<?php echo $this->marqueurs->url; ?>" />
            </td>
        </tr>
        <tr>
            <td class="key">
                <label for="lag">
                    <?php echo JText::_( 'CONTACTMAP_APERCU' ); ?>:
                </label>
            </td>
            <td>
                <img src="<?php echo $this->marqueurs->url; ?>" title="<?php echo $this->marqueurs->nom; ?>" />                 <?php echo JText::_( 'CONTACTMAP_ACTUALISER' ); ?>
            </td>

        </tr>
        <tr>
            <td width="120" class="key">
                <?php echo JText::_( 'CONTACTMAP_PUBLIE' ); ?>:
            </td>
            <td>
                <?php echo JHTML::_( 'select.booleanlist',  'published', 'class="inputbox"', $this->marqueurs->published ); ?>
            </td>
        </tr>
    </table>
    </fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_contactmap" />
<input type="hidden" name="id" value="<?php echo $this->marqueurs->id; ?>" />
<input type="hidden" name="task" value="edit" />
<input type="hidden" name="controller" value="marqueurs" />
</form>
<div class="copyright" align="center">
    <br />
    <?php echo JText::_( 'CONTACTMAP_COPYRIGHT' );?>
</div>

<?php
    /*
    * ContactMap Component Google Map for Joomla! 1.5.x
    * Version 3.4
    * Creation date: Octobre 2009
    * Author: Fabrice4821 - www.gmapfp.francejoomla.net
    * Author email: fayauxlogescpa@gmail.com
    * License GNU/GPL
    */

defined('_JEXEC') or die('Restricted access');
?>

<script language="javascript" type="text/javascript">
   function control() {
    var form = document.adminForm;
    // do field validation
    if (form.image1.value == ""){
     alert( "<?php echo JText::_('CONTACTMAP_UPLOAD_ERREUR'); ?>" );
    } else {
     return true;
    }
    return false;

   }
  </script> 
    <style>
.upload {font-family: Geneva, Arial, Helvetica, sans-serif;font-weight: bold;font-size: 12px;color: #3D5EA0;margin: 5px 0;text-align: left;}
.inputbox {font-size: 11px;}
    </style>
    <form action='index2.php?option=com_contactmap&controller=contactmap&task=upload_image' method='post' id='adminForm' name='adminForm' enctype='multipart/form-data' onsubmit='return control();'>
    <table width='100%' border='0' cellpadding='4' cellspacing='2' class='adminForm'>
    <div class="upload"><?php echo JText::_('CONTACTMAP_TELECHARGER_IMAGE') ;?>
    <tr align='left' valign='middle'>
    <td class='upload' align='left' valign='top'>
    <input type='hidden' name='option' value='com_contactmap' />
    <input class='inputbox' type='file' name='image1' /><br />
    <input type='hidden' name='no_html' value='no_html' />
    <input class='upload' type='submit' value='<?php echo JText::_('CONTACTMAP_BP_UPLOAD') ;?>' />
    </td></tr></form></table></div>
    </table></table>


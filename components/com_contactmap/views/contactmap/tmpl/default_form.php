<?php
    /*
    * ContactMap Component Google Map for Joomla! 1.5.x
    * Version 3.3
    * Creation date: Juillet 2010
    * Author: Fabrice4821 - www.gmapfp.org
    * Author email: fayauxlogescpa@gmail.com
    * License GNU/GPL
    */

defined('_JEXEC') or die('Restricted access');

	$script = '<!--
		function validateForm( frm ) {
			var valid = document.formvalidator.isValid(frm);
			if (valid == false) {
				// do field validation
				if (frm.email.invalid) {
					alert( "' . JText::_( 'Please enter a valid e-mail address.', true ) . '" );
				} else if (frm.text.invalid) {
					alert( "' . JText::_( 'CONTACT_FORM_NC', true ) . '" );
				}
				return false;
			} else {
				frm.submit();
			}
		}
		// -->';
	$document =& JFactory::getDocument();
	$document->addScriptDeclaration($script);
	
	if(isset($this->error)) : ?>
<tr>
	<td><?php echo $this->error; ?></td>
</tr>
<?php endif;

$Lig_Col=$this->params->get('affichage_colonne', 1);

$row = $this->rows[0];
$recipient = $row->email_to;

$mod_class_suffix = $this->params->get('pageclass_sfx');

$user   = & JFactory::getUser();
$CORRECT_NOM=$user->name;
$CORRECT_EMAIL=$user->email;
$CORRECT_SUBJECT = '';
$CORRECT_MESSAGE = '';

if ($recipient === "") {
	JError::raiseWarning(0, JText::_('CONTACTMAP_EMAIL_RECEPTEUR'));
}
?>    
    <div class="contactmap_contact <?php echo $mod_class_suffix ?>">
	<form action="<?php echo JRoute::_( 'index.php' ,false);?>" method="post" name="emailForm" id="emailForm" class="form-validate">
        <table>
            <tr><td>
                <table>
                    <tr>
                        <td>
							<label for="contactmap_nom">
								<?php echo JText::_('CONTACTMAP_EMAIL_NOM') ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td><input class="contactmap inputbox" type="text" id="contactmap_nom" name="contactmap_nom" size="30" value="<?php echo $CORRECT_NOM?>"/></td>
                    </tr>
                    <tr>
                        <td>
							<label for="contactmap_email">
								<?php echo JText::_('CONTACTMAP_VOTRE_EMAIL') ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td><input class="contactmap inputbox required validate-email" type="text" id="contactmap_email" name="contactmap_email" size="30" value="<?php echo $CORRECT_EMAIL?>"/></td>
                    </tr>
                    <tr>
                        <td>
							<label for="contactmap_subject">
								<?php echo JText::_('CONTACTMAP_EMAIL_SUJET') ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td><input class="contactmap inputbox" type="text" id="contactmap_subject" name="contactmap_subject" size="30" value="<?php echo $CORRECT_SUBJECT?>"/></td>
                    </tr>
                </table>
            <?php
                    if ($Lig_Col) {
                        echo '</td>';
                        echo '<td>';
                    }
            ?>
                <table>
                    <tr>
                        <td valign="top">
							<label for="contactmap_message">
								<?php echo JText::_('CONTACTMAP_EMAIL_MESSAGE') ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td><textarea class="contactmap textarea required" id="contactmap_message" name="contactmap_message" cols="50" rows="8"><?php echo $CORRECT_MESSAGE?></textarea></td>
                    </tr>
                </table>
            </td></tr>
                <tr>
                     <td>
                        <input type="checkbox" name="contact_email_copy" id="contact_email_copy" value="1" />
                        <label for="contact_email_copy">
                            <?php echo JText::_( 'CONTACTMAP_EMAIL_A_COPY' ); ?>
                        </label>
                    </td>
               </tr>
            <tr>
                <td colspan="2">
					<?php if (($this->params->get('contactmap_afficher_captcha'))&&!$user->get('gid')) { ?>
                	<div>
						<?php $link = JURI :: root().'index.php?option=com_contactmap&task=captacha&tmpl=component'; ?>
						<p>
							<img class="captcha" onclick="jcomments.clear('captcha');" id="comments-form-captcha-image" name="captcha-image" src="<?php echo $link; ?>" width="121" height="60" />
							<script type="text/javascript" src="<?php echo JURI :: root() ?>components/com_contactmap/libraries/reload.js"></script> 
							<a href="javascript:ReloadCaptchaImages('captcha-image');">
              					<img alt='reload.gif' src="<?php echo JURI :: root() ?>components/com_contactmap/images/reload.gif" id='ContactCaptchaReload' name='ContactCaptchaReload' border="0" />
           					</a> 
                        </p>
						<label for="keystring">
                        <?php echo JText::_('CONTACTMAP_CAPTCHA') ?><br />
						</label>
        				<input class="contactmap inputbox required" type="text" id="keystring" name="keystring">
   					</div>
                    <?php }; ?>
					<br />
					<button class="button validate" type="submit"><?php echo JText::_('CONTACTMAP_EMAIL_BOUTON'); ?></button>
           		</td>
           </tr>
        </table>
	<input type="hidden" name="option" value="com_contactmap" />
	<input type="hidden" name="view" value="contactmap" />
	<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
	<input type="hidden" name="task" value="submit" />
	<input type="hidden" name="code" value="<?php echo base64_encode($recipient); ?>" />
	<input type="hidden" name="code2" value="<?php echo base64_encode(JRoute::_( 'index.php',false )); ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
    </form>

   </div>

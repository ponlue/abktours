<?php
/**
* @version		$Id: default.php 17780 2010-06-20 09:03:02Z dextercowley $
* @package		Joomla.Site
* @subpackage	mod_search
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
* @license		GNU General Public License version 2 or later; see LICENSE.txt
*/

// no direct access
defined('_JEXEC') or die;
?><form action="<?php echo JRoute::_('index.php');?>" method="post"><div class="search<?php echo $params->get('moduleclass_sfx') ?>"><?php
$output = '<input name="searchword" id="mod_search_searchword" maxlength="'.$maxlength.'"  class="inputbox'.$moduleclass_sfx.'" type="text" size="'.$width.'" value="'.$text.'"  onblur="if (this.value==\'\') this.value=\''.$text.'\';" onfocus="if (this.value==\''.$text.'\') this.value=\'\';" />';

if ($button) :
if ($imagebutton) :
$button = '<input type="image" value="'.$button_text.'" class="button'.$moduleclass_sfx.' special'.$button_pos.'" src="'.$img.'" onclick="this.form.searchword.focus();"/>';
else :
$button = '<input type="submit" value="'.$button_text.'" class="button'.$moduleclass_sfx.' special'.$button_pos.'" onclick="this.form.searchword.focus();"/>';
endif;
endif;

switch ($button_pos) :
case 'top' :
	$button = $button.'<br />';
	$output = $button.$output;
	break;

case 'bottom' :
	$button = '<br />'.$button;
	$output = $output.$button;
	break;

case 'right' :
	$output = $output.$button;
	break;

case 'left' :
default :
	$output = $button.$output;
	break;
	endswitch;

	echo $output;
	?> <input type="hidden" name="task" value="search" /> <input	type="hidden" name="option" value="com_search" /> <input type="hidden"	name="Itemid" value="<?php echo $mitemid; ?>" /></div></form>
<?php 
	/*
	* ContactMap Component Google Map for Joomla! 1.5.x
	* Version 1.0
	* Creation date: Aout 2009
	* Author: Fabrice4821 - www.gmapfp.francejoomla.net
	* Author email: fayauxlogescpa@gmail.com
	* License GNU/GPL
	*/

defined('_JEXEC') or die('Restricted access'); 

foreach ($this->rows as $row) { ?>
	<h2>
	<?php echo JText::_('CONTACTMAP_HORAIRES_PRIX'); ?>
    </h2>
	<?php echo $row->horaires_prix;
};?>

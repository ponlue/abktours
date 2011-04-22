<?php
    /*
    * ContactMap Component Google Map for Joomla! 1.5.x
    * Version 2.0
    * Creation date: Decembre 2009
    * Author: Fabrice4821 - www.gmapfp.francejoomla.net
    * Author email: fayauxlogescpa@gmail.com
    * License GNU/GPL
    */

defined('_JEXEC') or die('Restricted access');

require_once (JPATH_COMPONENT.DS.'controller.php');
require_once( JPATH_COMPONENT.DS.'helper.php' );

// Require the base controller

$controllerName = JRequest::getWord('controller');

switch ($controllerName) {
case  "accueil" :
    JSubMenuHelper::addEntry(JText::_('CONTACTMAP_ACCUEIL'), 'index.php?option=com_contactmap&controller=accueil&task=view', true );
    JSubMenuHelper::addEntry(JText::_('CONTACTMAP_LIEUX'), 'index.php?option=com_contactmap&controller=contactmap&task=view');
	JSubMenuHelper::addEntry(JText::_('CONTACTMAP_CATEGORIES'), 'index.php?option=com_categories&section=com_contact_details');
    JSubMenuHelper::addEntry(JText::_('CONTACTMAP_MARQUEURS'), 'index.php?option=com_contactmap&controller=marqueurs&task=view');
    JSubMenuHelper::addEntry('CSS', 'index.php?option=com_contactmap&controller=css&task=view');
    break;
case  "marqueurs" :
    JSubMenuHelper::addEntry(JText::_('CONTACTMAP_ACCUEIL'), 'index.php?option=com_contactmap&controller=accueil&task=view');
    JSubMenuHelper::addEntry(JText::_('CONTACTMAP_LIEUX'), 'index.php?option=com_contactmap&controller=contactmap&task=view');
	JSubMenuHelper::addEntry(JText::_('CONTACTMAP_CATEGORIES'), 'index.php?option=com_categories&section=com_contact_details');
    JSubMenuHelper::addEntry(JText::_('CONTACTMAP_MARQUEURS'), 'index.php?option=com_contactmap&controller=marqueurs&task=view', true );
    JSubMenuHelper::addEntry('CSS', 'index.php?option=com_contactmap&controller=css&task=view');
    break;
case  "css" :
    JSubMenuHelper::addEntry(JText::_('CONTACTMAP_ACCUEIL'), 'index.php?option=com_contactmap&controller=accueil&task=view');
    JSubMenuHelper::addEntry(JText::_('CONTACTMAP_LIEUX'), 'index.php?option=com_contactmap&controller=contactmap&task=view');
	JSubMenuHelper::addEntry(JText::_('CONTACTMAP_CATEGORIES'), 'index.php?option=com_categories&section=com_contact_details');
    JSubMenuHelper::addEntry(JText::_('CONTACTMAP_MARQUEURS'), 'index.php?option=com_contactmap&controller=marqueurs&task=view');
    JSubMenuHelper::addEntry('CSS', 'index.php?option=com_contactmap&controller=css&task=view', true );
    break;
case "contactmap" :
    JSubMenuHelper::addEntry(JText::_('CONTACTMAP_ACCUEIL'), 'index.php?option=com_contactmap&controller=accueil&task=view');
    JSubMenuHelper::addEntry(JText::_('CONTACTMAP_LIEUX'), 'index.php?option=com_contactmap&controller=contactmap&task=view', true );
	JSubMenuHelper::addEntry(JText::_('CONTACTMAP_CATEGORIES'), 'index.php?option=com_categories&section=com_contact_details');
    JSubMenuHelper::addEntry(JText::_('CONTACTMAP_MARQUEURS'), 'index.php?option=com_contactmap&controller=marqueurs&task=view');
    JSubMenuHelper::addEntry('CSS', 'index.php?option=com_contactmap&controller=css&task=view');
    break;
default :
    JSubMenuHelper::addEntry(JText::_('CONTACTMAP_ACCUEIL'), 'index.php?option=com_contactmap&controller=accueil&task=view', true);
    JSubMenuHelper::addEntry(JText::_('CONTACTMAP_LIEUX'), 'index.php?option=com_contactmap&controller=contactmap&task=view');
	JSubMenuHelper::addEntry(JText::_('CONTACTMAP_CATEGORIES'), 'index.php?option=com_categories&section=com_contact_details');
    JSubMenuHelper::addEntry(JText::_('CONTACTMAP_MARQUEURS'), 'index.php?option=com_contactmap&controller=marqueurs&task=view');
    JSubMenuHelper::addEntry('CSS', 'index.php?option=com_contactmap&controller=css&task=view');
}

// Require specific controller if requested
if (!($controllerName = JRequest::getWord('controller')))
    {$controllerName = 'accueil';};


if($controllerName)
    {
        $path = JPATH_COMPONENT.DS.'controllers'.DS.$controllerName.'.php';
        if( file_exists($path))
            {
                require_once $path;
            } else
            {
                $controllerName = '';
            }
    }
$classname = 'ContactMapsController'.$controllerName;

// Create the controller
$controllerName = new $classname();

// Perform the Request task
if (!(JRequest::getVar('task'))) {
    $task = 'view';
}else{
    $task = JRequest::getVar('task');
};
$controllerName->execute( $task );

// Redirect if set by the controller
$controllerName->redirect();


?>

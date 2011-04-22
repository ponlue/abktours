<?php
    /*
    * ContactMap Component Google Map for Joomla! 1.5.x
    * Version 3.9
    * Creation date: Octobre 2010
    * Author: Fabrice4821 - www.gmapfp.org
    * Author email: webmaster@gmapfp.org
    * License GNU/GPL
    */

jimport( 'joomla.application.component.view');

class ContactMapsViewContactMap extends JView
{
    function display($tpl = null)
    {
        global $mainframe, $option, $Itemid;

		$user		= &JFactory::getUser();
        $document   = &JFactory::getDocument();

        $params = clone($mainframe->getParams('com_contactmap'));

        // Parametres
        $params->def('show_headings',           1);

        //ajout du fil d'actualité
        if($params->get('show_feed_link', 1) == 1)
        {
            $link   = '&format=feed&limitstart=';
            $attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
            $document->addHeadLink(JRoute::_($link.'&type=rss'), 'alternate', 'rel', $attribs);
            $attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
            $document->addHeadLink(JRoute::_($link.'&type=atom'), 'alternate', 'rel', $attribs);
        }

        $model      = &$this->getModel(); 
		//die(print_r($model));
        $rows       = $model->getContactMapList();
       	$map        = $model->getView();

		$contact = $rows[0];
		// check if we have a contact
		if (!is_object( $contact )) {
			JError::raiseError( 404, 'Contact not found' );
			return;
		}
		
		// check if access is registered/special
		if (($contact->access > $user->get('aid', 0)) || ($contact->category_access > $user->get('aid', 0))) {
			$uri		= JFactory::getURI();
			$return		= $uri->toString();
			
			$url  = 'index.php?option=com_user&view=login';
			$url .= '&return='.base64_encode($return);
			
			$mainframe->redirect($url, JText::_('You must login first') );
			
		}

		JHTML::_('behavior.formvalidation');

        $this->assignRef('map'          , $map );	        
        $this->assignRef('rows'         , $rows);
        $this->assignRef('params'       , $params);

        parent::display($tpl);
    }   
}
?>

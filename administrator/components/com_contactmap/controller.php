<?php
    /*
    * ContactMap Component Google Map for Joomla! 1.5.x
    * Version 6.8
    * Creation date: Aout 2009
    * Author: Fabrice4821 - www.gmapfp.francejoomla.net
    * Author email: fayauxlogescpa@gmail.com
    * License GNU/GPL
    */

jimport('joomla.application.component.controller');

class ContactMapsController extends JController
{
    function display()
    {
        parent::display();
    }
    
    function orderup()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'Invalid Token' );

        global $mainframe, $option;
        $filter_order       = $mainframe->getUserStateFromRequest( $option.'filter_order',      'filter_order',     'a.ordering',   'cmd' );
        $filter_order_Dir   = $mainframe->getUserStateFromRequest( $option.'filter_order_Dir',  'filter_order_Dir',     '',                                             'word');
        $model = $this->getModel('contactmap');
        
        if (($filter_order=='a.ordering')and($filter_order_Dir=='asc')) {
            $model->move(-1);
        };
        if (($filter_order=='a.ordering')and($filter_order_Dir=='desc')) {
            $model->move(1);
        };

        $this->setRedirect( 'index.php?option=com_contactmap&controller=contactmap&task=view');
    }

    function orderdown()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'Invalid Token' );

        global $mainframe, $option;
        $filter_order       = $mainframe->getUserStateFromRequest( $option.'filter_order',      'filter_order',     'a.ordering',   'cmd' );
        $filter_order_Dir   = $mainframe->getUserStateFromRequest( $option.'filter_order_Dir',  'filter_order_Dir',     '',                                             'word');
        $model = $this->getModel('contactmap');
        
        if (($filter_order=='a.ordering')and(($filter_order_Dir=='asc')or($filter_order_Dir==''))) {
            $model->move(1);
        };
        if (($filter_order=='a.ordering')and($filter_order_Dir=='desc')) {
            $model->move(-1);
        };

        $this->setRedirect( 'index.php?option=com_contactmap&controller=contactmap&task=view');
    }

    function saveorder()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'Invalid Token' );

        $cid    = JRequest::getVar( 'cid', array(), 'post', 'array' );
        $order  = JRequest::getVar( 'order', array(), 'post', 'array' );
        JArrayHelper::toInteger($cid);
        JArrayHelper::toInteger($order);

        $model = $this->getModel('contactmap');
        $model->saveorder($cid, $order);

        $msg = JText::_( 'New ordering saved' );
        $this->setRedirect( 'index.php?option=com_contactmap&controller=contactmap&task=view', $msg );
    }

}
?>

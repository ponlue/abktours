<?php
    /*
    * ContactMap Component Google Map for Joomla! 1.5.x
    * Version 2.3
    * Creation date: Juin 2010
    * Author: Fabrice4821 - www.gmapfp.org
    * Author email: fayauxlogescpa@gmail.com
    * License GNU/GPL
    */

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );
jimport('joomla.html.pane');

class ContactMapsViewAccueil extends JView
{
    function display($tpl = null)
    {
        global $mainframe;

        JToolBarHelper::title( JText::_( 'CONTACTMAP_ACCUEIL_MANAGER' ),'cpanel.png' );

        parent::display($tpl);
    }

    /**
     * Donation pour CONTACTMAP
     */
    function Infos_Donation(){

        global $mainframe;
        $lang       =& JFactory::getLanguage();
        $langue     = $lang->getTag();
        $langue=str_replace('-','_',$langue);
        
        $template   = $mainframe->getTemplate();
        $tag_lang=(substr($lang->getTag(),3,2)); 
    
        $output = '<div style="padding: 5px;">';
        $output .= '<span style="font-size:120%;">'.JText::_('CONTACTMAP_EXPLICATION_DONATION');
        $output .= '<br /><span style="color:#0000FF; font-size:170%; font-weight:bold;">'.JText::_('CONTACTMAP_SOMME_DONT').'</span>';
        $output .= JText::_('CONTACTMAP_EXPLICATION2_DONATION').'</span>';
        $output .= '<br /><br /><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=f_pelletier%40yahoo%2ecom&lc="'.$tag_lang.'&item_name=Donation%20for%20ContactMap%20services&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHostedGuest"><img src="https://www.paypal.com/'.$langue.'/i/btn/btn_donate_SM.gif">
                        </a><br /><br /><br />';
        $output .= '</div>';
        return $output;
    }

    /**
     * News du site de CONTACTMAP
     */
    function Infos_News() {
        
        $output = '';

        //  get RSS parsed object
        $options = array();
        $options['rssUrl']      = 'http://www.gmapfp.org/fr/newscontactmap';
        $options['cache_time']  = 43200;

        $rssDoc =& JFactory::getXMLparser('RSS', $options);

        if ( $rssDoc == false ) {
            $output = JText::_('Error: Feed not retrieved');
        } else {    
            // channel header and link
            $title  = $rssDoc->get_title();
            $link   = $rssDoc->get_link();
            
            $output = '<table class="adminlist">';
            
            $items = array_slice($rssDoc->get_items(), 0, 3);
            $numItems = count($items);
            if($numItems == 0) {
                $output .= '<tr><th>' .JText::_('No news items found'). '</th></tr>';
            } else {
                $k = 0;
                for( $j = 0; $j < $numItems; $j++ ) {
                    $item = $items[$j];
                    $output .= '<tr><td class="row' .$k. '">';
                    $output .= '<a href="' .$item->get_link(). '" target="_blank">' .$item->get_title(). '</a>';
                    if($item->get_description()) {
                        $description = $this->limitText($item->get_description(), 50);
                        $output .= '<br />' .$description;
                    }
                    $output .= '</td></tr>';
                }
            }
            $k = 1 - $k;
                        
            $output .= '</table>';
        }       
        return $output;
     }

    function limitText($text, $wordcount)
    {
        if(!$wordcount) {
            return $text;
        }

        $texts = explode( ' ', $text );
        $count = count( $texts );

        if ( $count > $wordcount )
        {
            $text = '';
            for( $i=0; $i < $wordcount; $i++ ) {
                $text .= ' '. $texts[$i];
            }
            $text .= '...';
        }

        return $text;
    }
         

}

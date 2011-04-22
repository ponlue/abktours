<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: view.html.php 2 2010-04-13 13:37:46Z WEB $
 * @copyright       Copyright 2009-2010, $Author: WEB $
 * @license         GNU General Public License (GNU GPL) GPLv2, 
 *                  - see http://www.demo-page.de/en/license-conditions.html
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 *    See the GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @link            http://www.demo-page.de
 * @package         TRAVELbook Component
 * @revision        $Revision: 2 $
 * @lastmodified    $Date: 2010-04-13 15:37:46 +0200 (Di, 13 Apr 2010) $
*/

/*** No direct access ***/
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.html.pane');
jimport( 'joomla.application.component.view');

/**
 * travelbook View
 *
 * @package    travelbook
 * @subpackage administrator
 */ 
class TravelbooksViewTravelbooks extends JView
{
	/** 
	 * display method of travelbook view
	 * @return void
	 **/
	function display($tpl = null)
	{


		$document = &JFactory::getDocument();
		$document->addStyleSheet( 'components/com_travelbook/assets/css/travelbook.css' );

		JToolBarHelper::title(   JText::_( 'TB TRAVELbook' ).' <small><small> ' .JText::_( 'TB On Tour' ).' </small></small>' , 'home');

		parent::display($tpl);
	}
	 /**
	 * This method creates a standard cpanel button
	 *
	 * @param string $link
	 * @param string $image
	 * @param string $text
	 * @param string $path
	 * @param string $target
	 * @param string $onclick
	 * @access protected
	 */
	 function _quickiconButton( $link, $image, $text, $nl=false, $path=null, $target='', $onclick='' ) {
	 	if( $target != '' ) {
	 		$target = 'target="' .$target. '"';
	 	}
	 	if( $onclick != '' ) {
	 		$onclick = 'onclick="' .$onclick. '"';
	 	}
	 	if( $path === null || $path === '' ) {
	 		$path = 'components/com_travelbook/assets/images/';
	 	}
		?>

		<?php 
			if ($nl) { 
				echo " <div style='clear:left;'>"; 
			} else {
				echo " <div style='float:left;'>"; 
			} 
		?>
				<div class="icon">
					<a href="<?php echo $link; ?>" <?php echo $target;?>  <?php echo $onclick;?>>
						<?php echo JHTML::_('image.administrator', $image, $path, NULL, NULL, $text ); ?>
						<span><?php echo $text; ?></span>
					</a>
				</div>
			</div>
		<?php
	 }

	 /**
	  * render Tours module
	  */
	 function renderTours () {
	 	$output = '';

		$output = '<div style="padding: 5px;">';
		$output .= "<table class='adminlist'>\n";

		$output .= "	<thead>\n";
		$output .= "		<tr class='sortable'>\n";
		$output .= "			<th width='10'> ".JText::_( 'Num' )."</th>\n";
		$output .= "			<th  nowrap='nowrap' style='text-align: left;'> ".JText::_( 'TB Tour' )."</th>\n";
		$output .= "			<th  width='30%' nowrap='nowrap' style='text-align: left;'> ".JText::_( 'TB Category' )."</th>\n";
		$output .= "			<th  width='20%' nowrap='nowrap' style='text-align: center;'> ".JText::_( 'TB Year' )."</th>\n";
		$output .= "		</tr>\n";
		$output .= "	</thead>\n";

		$tours	=& $this->get('tours');
		$i = 0;
		foreach ($tours as $tour) {
			$i++;
			$link = JRoute::_( 'index.php?option=com_travelbook&task=tours.detail&tid[]='. $tour->id );

			$output .= "	<tr class='row".$tour->id."'>\n";
			$output .= "		<td>\n";
			$output .= $i;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: left;'>\n";
			$output .= "<a href='".$link."'>".$tour->title."</a> ";
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: left;'>\n";
			$output .= $tour->category;
			$output .=  "		</td>\n";
			$output .= "		<td style='text-align: center;'>\n";
			$output .= $tour->year;
			$output .=  "		</td>\n";
			$output .=  "	</tr>\n";
		}

		$output .= "</table>\n";
		$output .= '</div>';
		
		return $output;
	 }
	 
	 /**
	  * render News feed from Twitter
	  */
	 function rendertravelbookNews() {
	 	
	 	$output = '';

		//  get RSS parsed object
		$options = array();
		$options['rssUrl']		= 'http://search.twitter.com/search?q=%23travelbook';
		$options['cache_time']	= 86400;

		$rssDoc =& JFactory::getXMLparser('RSS', $options);

		if ( $rssDoc == false ) {
			$output = JText::_('TB News not retrieved');
		} else {	
			// channel header and link
			$title 	= $rssDoc->get_title();
			$link	= $rssDoc->get_link();
			
			$output = '<table class="adminlist">';
			$output .= '<tr><th colspan="3"><a href="'.$link.'" target="_blank">'.JText::_($title) .'</th></tr>';
			$output .= '<tr><td colspan="3">'.JText::_('TB TWITTERNEWS INTRODUCTION').'</td></tr>';
			
			$items = array_slice($rssDoc->get_items(), 0, 3);
			$numItems = count($items);
            if($numItems == 0) {
            	$output .= '<tr><th>' .JText::_('TB No news found'). '</th></tr>';
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

	 /**
	  * render News feed from demo-page.de
	  */
	 function renderNews() {
	 	
	 	$output = '';

		$config = &JFactory::getConfig();
		$locale    = $config->getValue('config.language');  
		list($loc1, $loc2) = explode('-', $locale);

		//  get RSS parsed object
		$options = array();
		$options['rssUrl']		= 'http://www.demo-page.de/'.$loc1.'/news?format=feed&type=rss';
//	$options['rssUrl']		= 'http://www.demo-page.de/'.$loc1.'de/news.feed?type=rss';
		$options['cache_time']	= 86400;

		$rssDoc =& JFactory::getXMLparser('RSS', $options);

		if ( $rssDoc == false ) {
			$output = JText::_('TB Feed not retrieved');
		} else {	
			// channel header and link
			$title 	= $rssDoc->get_title();
			$link	= $rssDoc->get_link();
			
			$output = '<table class="adminlist">';
			$output .= '<tr><th colspan="3"><a href="'.$link.'" target="_blank">'.JText::_($title) .'</th></tr>';
			$output .= '<tr><td colspan="3">'.JText::_('TB NEWS INTRODUCTION').'</td></tr>';
			
			$items = array_slice($rssDoc->get_items(), 0, 3);
			$numItems = count($items);
            if($numItems == 0) {
            	$output .= '<tr><th>' .JText::_('TW No news items found'). '</th></tr>';
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
?>
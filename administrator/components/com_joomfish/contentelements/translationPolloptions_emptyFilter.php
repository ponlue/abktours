<?php
/**
 * Joom!Fish - Multi Lingual extention and translation manager for Joomla!
 * Copyright (C) 2003 - 2011, Think Network GmbH, Munich
 *
 * All rights reserved.  The Joom!Fish project is a set of extentions for
 * the content management system Joomla!. It enables Joomla!
 * to manage multi lingual sites especially in all dynamic information
 * which are stored in the database.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,USA.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * -----------------------------------------------------------------------------
 * $Id: translationPolloptions_emptyFilter.php 1251 2009-01-06 18:33:02Z apostolov $
 * @package joomfish
 * @subpackage TranslationFilters
 *
*/

// Don't allow direct linking
defined( '_JEXEC' ) or die( 'Restricted access' );

class translationPolloptions_emptyFilter extends translationFilter
{
	public function __construct ($contentElement){
		$this->filterNullValue=-1;
		$this->filterType="polloptions_empty";
		$this->filterField = $contentElement->getFilter("polloptions_empty");
		parent::__construct($contentElement);
	}
	
	public function createFilter(){
		$db = JFactory::getDBO();
		if (!$this->filterField ) return "";
		// always hide empty poll options
		$filter = 'c.'.$this->filterField.' !=""';
		return $filter;
	}
	

	/**
 * Creates vm_category filter 
 *
 * @param unknown_type $filtertype
 * @param unknown_type $contentElement
 * @return unknown
 */
	public function createFilterHTML(){
		return "";
	}

}
?>

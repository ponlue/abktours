<?php
/**
 * "TRAVELbook - JOOMLA! on Tour"
 *
 * @version         $Id: travelbook.php 2 2010-04-13 13:37:46Z WEB $
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
defined('_JEXEC') or die('Restricted access');

/**
 * TRAVELbook Component Helper
 *
 * @static
 * @package		Joomla
 * @subpackage	Content
 * @since 1.5
 */
class TravelbookHelperTravelbook
{

	/**
	 *
	 *  @param $VisitorIp - valid only when true is returned
	 *  @return true on success
	 */

	function getVisitorIp(&$VisitorIp)
	{
		$Ip_tmp = null;
		// get usefull vars:
		$client_ip       = isset($_SERVER['HTTP_CLIENT_IP'])       ? $_SERVER['HTTP_CLIENT_IP']	      : NULL;
		$x_forwarded_for = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : NULL;
		$remote_addr     = isset($_SERVER['REMOTE_ADDR'])          ? $_SERVER['REMOTE_ADDR']	      : NULL;

		// then the script itself
		if (!empty($x_forwarded_for) && strrpos($x_forwarded_for, '.') > 0)
		{
			$arr = explode(',', $x_forwarded_for);
			$Ip_tmp = trim(end($arr));
		}

		if (!TravelbookHelperTravelbook::isIpAddressValidRfc3330($Ip_tmp) && !empty($client_ip))
		{
			$ip_expl = explode('.', $client_ip);
			$referer = explode('.', $remote_addr);

			if ($referer[0] != $ip_expl[0])
			{
				$Ip_tmp = trim(implode('.', array_reverse($ip_expl)));
			}
			else
			{
				$arr = explode(',', $client_ip);
				$Ip_tmp = trim(end($arr));
			}
		}

		if (!TravelbookHelperTravelbook::isIpAddressValidRfc3330($Ip_tmp) && !empty($remote_addr))
		{
			$arr = explode(',', $remote_addr);
			$Ip_tmp = trim(end($arr));
		}

		unset($client_ip, $x_forwarded_for, $remote_addr, $ip_expl, $referer);

		$VisitorIp = $Ip_tmp;
		return true;
	}

	function isIpAddressValidRfc3330( $ipAddress ) {

		$substr2 = substr( $ipAddress, 0, 2 );
		$substr3 = substr( $ipAddress, 0, 3 );
		$substr4 = substr( $ipAddress, 0, 4 );
		$substr6 = substr( $ipAddress, 0, 6 );
		$substr8 = substr( $ipAddress, 0, 8 );
		$substr10 = substr( $ipAddress, 0, 10 );
		$substr12 = substr( $ipAddress, 0, 12 );
		$IpAsLong = sprintf( "%u", ip2long( $ipAddress ) );

		return ( ( $ipAddress != NULL ) &&
			( $substr2 !== '0.' )     // Reserved IP block
			&& ( $substr3 !== '10.' ) // Reserved for private networks
			&& ( $substr3 !== '14.' ) // IANA Public Data Network
			&& ( $substr3 !== '24.' ) // Reserved IP block
			&& ( $substr3 !== '27.' ) // Reserved IP block
			&& ( $substr3 !== '39.' ) // Reserved IP block
			&& ( $substr4 !== '127.' ) // Reserved IP block
			&& ( $substr6 !== '128.0.' ) // Reserved IP block
			&& ( $substr8 !== '169.254.' ) // Reserved IP block
			&& ( ( $IpAsLong < sprintf( "%u", ip2long( '172.16.0.0' ) ) ) // Private networks
				|| $IpAsLong > sprintf( "%u", ip2long( '172.31.255.255' ) ) ) 
			&& ( $substr8 !== '191.255.' ) // Reserved IP block
			&& ( $substr8 !== '192.0.0.' ) // Reserved IP block
			&& ( $substr8 !== '192.0.2.' ) // Reserved IP block
			&& ( $substr10 !== '192.88.99.' ) // Reserved IP block
			&& ( $substr8 !== '192.168.' ) // Reserved IP block
			&& ( ( $IpAsLong < sprintf( "%u", ip2long( '198.18.0.0' ) ) ) // Multicast addresses
				|| ( $IpAsLong > sprintf( "%u", ip2long( '198.19.255.255' ) ) ) )
			&& ( $substr12 !== '223.255.255.' ) // Reserved IP block
			&& ( ( $IpAsLong < sprintf( "%u", ip2long( '224.0.0.0' ) ) ) // Multicast addresses
				|| ( $IpAsLong > sprintf( "%u", ip2long( '239.255.255.255' ) ) ) )
			&& ( ( $IpAsLong < sprintf( "%u", ip2long( '240.0.0.0' ) ) ) // Reserved IP blocks
				|| ( $IpAsLong > sprintf( "%u", ip2long( '255.255.255.255' ) ) ) )
		);

	}

}
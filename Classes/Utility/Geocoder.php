<?php
namespace Webfox\Placements\Utility;

/** *************************************************************
 *
 * Geocoding utility 
 *
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

Class Geocoder {
	static private $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=";

	static public function getLocation($address){
		$url = self::$url.urlencode($address);
		
		$resp_json = \TYPO3\CMS\Core\Utility\GeneralUtility::getUrl($url);
		$resp = json_decode($resp_json, true);

		if($resp['status']='OK'){
			return $resp['results'][0]['geometry']['location'];
		}else{
			return false;
		}
	}
}
 
?>


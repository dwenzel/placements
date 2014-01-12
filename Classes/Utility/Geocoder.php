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

	// calculate destination lat/lng given a starting point, bearing, and distance
	public function destination($lat,$lng, $bearing, $distance,$units="km") {
    $radius = strcasecmp($units, "km") ? 3963.19 : 6378.137;
    $rLat = deg2rad($lat);
    $rLon = deg2rad($lng);
    $rBearing = deg2rad($bearing);
    $rAngDist = $distance / $radius;

    $rLatB = asin(sin($rLat) * cos($rAngDist) + 
        cos($rLat) * sin($rAngDist) * cos($rBearing));

    $rLonB = $rLon + atan2(sin($rBearing) * sin($rAngDist) * cos($rLat), 
                           cos($rAngDist) - sin($rLat) * sin($rLatB));

    return array("lat" => rad2deg($rLatB), "lng" => rad2deg($rLonB));
	}
	
	// calculate bounding box
	public function getBoundsByRadius($lat,$lng, $distance,$units="km") {
		return array("N" => self::destination($lat,$lng,   0, $distance,$units),
			"E" => self::destination($lat,$lng,  90, $distance,$units),
			"S" => self::destination($lat,$lng, 180, $distance,$units),
			"W" => self::destination($lat,$lng, 270, $distance,$units));
	}

	// calculate distance between two lat/lon coordinates
	function distance($latA,$lonA, $latB,$lonB, $units="km") {
		$radius = strcasecmp($units, "km") ? 3963.19 : 6378.137;
		$rLatA = deg2rad($latA);
		$rLatB = deg2rad($latB);
		$rHalfDeltaLat = deg2rad(($latB - $latA) / 2);
		$rHalfDeltaLon = deg2rad(($lonB - $lonA) / 2);
		
		return 2 * $radius * asin(sqrt(pow(sin($rHalfDeltaLat), 2) +
			cos($rLatA) * cos($rLatB) * pow(sin($rHalfDeltaLon), 2)));
	}
}
 
?>


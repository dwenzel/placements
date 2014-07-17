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

class Geocoder {
	/**
	 * Service Url
	 *
	 * @var \string Base Url for geocoding service.
	 */
	protected $serviceUrl = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=";

	/**
	 * Returns the base url of the geocoding service
	 *
	 * @return \string
	 */
	public function getServiceUrl() {
		return $this->serviceUrl;
	}

	/**
	 * Get Geolocation encoded from Google Maps geocode service.
	 *
	 * @param \string $address An address to encode.
	 * @return \array Array containing geolocation information
	 */
	public function getLocation($address){
		$url = $this->serviceUrl . urlencode($address);
		
		$response_json = $this->getUrl($url); 
		$response = json_decode($response_json, true);
		if($response['status'] == 'OK'){
			return $response['results'][0]['geometry']['location'];
		}else{
			return FALSE;
		}
	}

	/**
	 * Get url
	 * Wrapper for GeneralUtility::getUrl to make it testable.
	 *
	 * @param \string $url File/Url to fetch
	 * @return mixed Response
	 * @codeCoverageIgnore
	 */
	public function getUrl($url) {
		return \TYPO3\CMS\Core\Utility\GeneralUtility::getUrl($url);
	}

	/**
	 * calculate destination lat/lng given a starting point, bearing, and distance
	 *
	 * @param \float $lat Latitude
	 * @param \float $lng Longitude
	 * @param \integer $distance Distance
	 * @param \string $units Units: default km. Any other value will result in computing with mile based constants.
	 * @return \array An array with lat and lng values
	 * @codeCoverageIgnore
	 */
	public function destination($lat,$lng, $bearing, $distance, $units="km") {
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
	
	/**
	 * calculate bounding box
	 *
	 * @param \float $lat Latitude of location
	 * @param \float $lng Longitude of location
	 * @param \float $distance Distance around location
	 * @param \string $units Unit: default km. Any other value will result in computing with mile based constants.
	 * @return \array An array describing a bounding box
	 * @codeCoverageIgnore
	 */
	public function getBoundsByRadius($lat, $lng, $distance, $units="km") {
		return array("N" => $this->destination($lat,$lng,   0, $distance,$units),
			"E" => $this->destination($lat,$lng,  90, $distance,$units),
			"S" => $this->destination($lat,$lng, 180, $distance,$units),
			"W" => $this->destination($lat,$lng, 270, $distance,$units));
	}

	/**
	 * calculate distance between two lat/lon coordinates
	 *
	 * @param \float $latA Latitude of location A
	 * @param \float $lonA Longitude of location A
	 * @param \float $latB Latitude of location B
	 * @param \float $lonB Longitude of location B
	 * @param \string $units Units: default km. Any other value will result in computing with mile based constants.
	 * @return \float
	 * @codeCoverageIgnore
	 */
	public function distance($latA,$lonA, $latB,$lonB, $units="km") {
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


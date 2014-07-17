<?php

namespace Webfox\Placements\Tests;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Dirk Wenzel <wenzel@webfox01.de>, AgenturWebfox GmbH
 *  			Michael Kasten <kasten@webfox01.de>, AgenturWebfox GmbH
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Test case for class \Webfox\Placements\Utility\Geocoder.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Placement Service
 *
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @author Michael Kasten <kasten@webfox01.de>
 */
class GeocoderTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getAccessibleMock('\Webfox\Placements\Utility\Geocoder',
				array('getUrl'), array(), '', FALSE);
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * Get test values for Google Maps geocoding api
	 * 
	 * @param \string $response Which result should be returned
	 * @return \string Mocked response json
	 */
	public function getGoogleMapsGeocodeApiResponse($response) {
		if($response == 'success') {
			$result = '{
   			"results" : [
      		{
         		"address_components" : [
           		 {
             		  "long_name" : "Leipzig",
               		"short_name" : "Leipzig",
               		"types" : [ "locality", "political" ]
            		},
           		 {
             		  "long_name" : "Leipzig",
               		"short_name" : "Leipzig",
               		"types" : [ "administrative_area_level_2", "political" ]
            		},
           		 {
             		  "long_name" : "Saxony",
              		 "short_name" : "SN",
              		 "types" : [ "administrative_area_level_1", "political" ]
           		 },
           		 {
             		  "long_name" : "Germany",
              		 "short_name" : "DE",
              	 "types" : [ "country", "political" ]
           		 }
        		 ],
         		"formatted_address" : "Leipzig, Germany",
         		"geometry" : {
          		  "bounds" : {
            		   "northeast" : {
              		    "lat" : 51.448026,
                		  "lng" : 12.5506377
              		 },
             		  "southwest" : {
             		     "lat" : 51.235539,
             		     "lng" : 12.2366321
              		 }
           		 },
           		 "location" : {
           		    "lat" : 51.3396955,
          		     "lng" : 12.3730747
          		  },
          		  "location_type" : "APPROXIMATE",
          		  "viewport" : {
            		   "northeast" : {
             		     "lat" : 51.448026,
              		   "lng" : 12.5506377
 		             	 },
             		  "southwest" : {
              		    "lat" : 51.235539,
                		  "lng" : 12.2366321
               		}
           			}
       		  },
       		  "types" : [ "locality", "political" ]
     		 }
   		],
   		"status" : "OK"
		}';
		} elseif ($respose == 'invalid_request') {
			$result = '{
   			"results" : [],
   			"status" : "INVALID_REQUEST"
			}';
		}
		return $result;
	}

	/**
	 * @test
	 */
	public function getServiceUrlReturnsInitialValueForString() {
		$this->assertSame(
				'http://maps.google.com/maps/api/geocode/json?sensor=false&address=',
				$this->fixture->getServiceUrl()
		);
	}

	/**
	 * @test
	 */
	public function getLocationBuildsCorrectUrl() {
		$expectedUrl = $this->fixture->getServiceUrl() . 'bogus';
		$this->fixture->expects($this->once())->method('getUrl')
			->with($expectedUrl);
		$this->fixture->getLocation('bogus');
	}
	/**
	 * @test
	 */
	public function getLocationReturnsFalseForInvalidRequest() {
		//$this->markTestSkipped();
		$response = $this->getGoogleMapsGeocodeApiResponse('invalid_request');
		$this->fixture->expects($this->once())->method('getUrl')
			->will($this->returnValue($response));

		$result = $this->fixture->_call('getLocation', 'bogus');
		$this->assertSame(
				FALSE,
				$result
		);
	}

	/**
	 * @test
	 */
	public function getLocationReturnsLocationForValidRequest() {
		//$this->markTestSkipped();
		$response = $this->getGoogleMapsGeocodeApiResponse('success');
		$result = array(
			'lat' => 51.3396955,
			'lng' => 12.3730747
		);

		$this->fixture->expects($this->once())->method('getUrl')
			->will($this->returnValue($response));

		$this->assertSame(
			$result,
			$this->fixture->_call('getLocation', 'bogus')
		);
	}
}
?>

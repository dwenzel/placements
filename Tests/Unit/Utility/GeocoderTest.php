<?php
namespace Webfox\Placements\Tests;
/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

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
 * @coversDefaultClass \Webfox\Placements\Utility\Geocoder
 */
class GeocoderTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
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
	private function getGoogleMapsGeocodeApiResponse($response) {
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
		if (method_exists($this->fixture, 'getServiceUrl')) {
			$this->assertSame(
					'http://maps.google.com/maps/api/geocode/json?sensor=false&address=',
					$this->fixture->getServiceUrl()
			);
		} else {
			$this->markTestSkipped();
		}
	}

	/**
	 * @test
	 */
	public function getLocationBuildsCorrectUrl() {
		if (method_exists($this->fixture, 'getServiceUrl')) {
			$expectedUrl = $this->fixture->getServiceUrl() . 'bogus';
			$this->fixture->expects($this->once())->method('getUrl')
				->with($expectedUrl);
			$this->fixture->getLocation('bogus');
		} else {
			$this->markTestSkipped();
		}
	}

	/**
	 * @test
	 */
	public function getLocationReturnsFalseForInvalidRequest() {
		$response = $this->getGoogleMapsGeocodeApiResponse('invalid_request');
		$this->fixture->expects($this->once())->method('getUrl')
			->will($this->returnValue($response));

		if (method_exists($this->fixture, 'getLocation')) {
			$result = $this->fixture->_call('getLocation', 'bogus');
			$this->assertSame(
					FALSE,
					$result
			);
		} else {
			$this->markTestSkipped();
		}
	}

	/**
	 * @test
	 */
	public function getLocationReturnsLocationForValidRequest() {
		$response = $this->getGoogleMapsGeocodeApiResponse('success');
		$result = array(
			'lat' => 51.3396955,
			'lng' => 12.3730747
		);

		$this->fixture->expects($this->once())->method('getUrl')
			->will($this->returnValue($response));
		
		if (method_exists($this->fixture, 'getLocation')) {
			$this->assertSame(
				$result,
				$this->fixture->_call('getLocation', 'bogus')
			);
		} else {
			$this->markTestSkipped();
		}
	}

	/**
	 * @test
	 * @covers ::updateGeoLocation
	 */
	public function updateGeoLocationInitiallyReturnsOriginalObject() {
		$fixture = $this->getMock(
			'Webfox\Placements\Utility\Geocoder',
			array('dummy'), array(), '', FALSE);
		$mockObject = $this->getMock(
			'Webfox\Placements\Domain\Model\GeocodingInterface',
			array(
				'setLatitude',
				'setLongitude',
				'getCity',
				'getZip',
				'getLatitude',
				'getLongitude'
				), array(), '', FALSE);

		$mockObject->expects($this->never())->method('setLatitude');
		$mockObject->expects($this->never())->method('setLongitude');
		if (method_exists($fixture, 'updateGeoLocation')) {
			$fixture->updateGeoLocation($mockObject);
		} else {
			$this->markTestSkipped();
		}
	}

	/**
	 * @test
	 * @covers ::updateGeoLocation
	 */
	public function updateGeoLocationReturnsOriginalObjectForInvalidCity() {
		$fixture = $this->getMock(
			'\Webfox\Placements\Utility\Geocoder',
			array('dummy'), array(), '', FALSE);
		$mockObject = $this->getMock(
			'\Webfox\Placements\Domain\Model\GeocodingInterface',
			array(
				'setLatitude',
				'setLongitude',
				'getCity',
				'getZip',
				'getLatitude',
				'getLongitude'
				), array(), '', FALSE);

		$mockObject->expects($this->once())->method('getCity');
		$mockObject->expects($this->never())->method('setLatitude');
		$mockObject->expects($this->never())->method('setLongitude');

		if (method_exists($fixture, 'updateGeoLocation')) {
			$fixture->updateGeoLocation($mockObject);
		} else {
			$this->markTestSkipped();
		}
	}

	/**
	 * @test
	 * @covers ::updateGeoLocation
	 */
	public function updateGeoLocationSetsLatitudeAndLongitude() {
		$fixture = $this->getMock(
			'\Webfox\Placements\Utility\Geocoder',
			array('dummy', 'getLocation'), array(), '', FALSE);
		$mockObject = $this->getMock(
			'\Webfox\Placements\Domain\Model\GeocodingInterface',
			array(
				'setLatitude',
				'setLongitude',
				'getCity',
				'getZip',
				'getLatitude',
				'getLongitude'
				), array(), '', FALSE);
		$responseSuccess = array(
			'lat' => 51.3396955,
			'lng' => 12.3730747
		);

		$mockObject->expects($this->once())->method('getCity')
			->will($this->returnValue('foo'));
		$mockObject->expects($this->once())->method('getZip')
			->will($this->returnValue('bar'));
		$fixture->expects($this->once())->method('getLocation')
			->with('bar foo')
			->will($this->returnValue($responseSuccess));
		$mockObject->expects($this->once())->method('setLatitude')
			->with($responseSuccess['lat']);
		$mockObject->expects($this->once())->method('setLongitude')
			->with($responseSuccess['lng']);

		if (method_exists($fixture, 'updateGeoLocation')) {
			$fixture->updateGeoLocation($mockObject);
		} else {
			$this->markTestSkipped();
		}
	}

	/**
	 * @test
	 * @covers ::updateGeoLocation
	 */
	public function updateGeoLocationDoesNotSetLatitudeAndLongitudeForFailedGetLocation() {
		$fixture = $this->getMock(
			'\Webfox\Placements\Utility\Geocoder',
			array('dummy', 'getLocation'), array(), '', FALSE);
		$mockObject = $this->getMock(
			'\Webfox\Placements\Domain\Model\GeocodingInterface',
			array(
				'setLatitude',
				'setLongitude',
				'getCity',
				'getZip',
				'getLatitude',
				'getLongitude'
				), array(), '', FALSE);

		$mockObject->expects($this->once())->method('getCity')
			->will($this->returnValue('foo'));
		$mockObject->expects($this->once())->method('getZip')
			->will($this->returnValue('bar'));
		$fixture->expects($this->once())->method('getLocation')
			->with('bar foo')
			->will($this->returnValue(FALSE));
		$mockObject->expects($this->never())->method('setLatitude');
		$mockObject->expects($this->never())->method('setLongitude');

		if (method_exists($fixture, 'updateGeoLocation')) {
			$fixture->updateGeoLocation($mockObject);
		} else {
			$this->markTestSkipped();
		}
	}
}

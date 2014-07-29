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
 * Test case for class \Webfox\Placements\Domain\Model\Dto\AbstractDemand.
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
class AbstractDemandTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\Placements\Domain\Model\Dto\AbstractDemand
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \Webfox\Placements\Domain\Model\Dto\AbstractDemand();
	}

	public function tearDown() {
		unset($this->fixture);
	}
	
	/**
	 * @test
	 */
	public function getOrderReturnsInitialValueForString() {
		$this->assertNull($this->fixture->getOrder());
	}

	/**
	 * @test
	 */
	public function setOrderForStringSetsOrder() { 
		$this->fixture->setOrder('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getOrder()
		);
	}
	
	/**
	 * @test
	 */
	public function getStatusReturnsInitialValueForInteger() {
		$this->assertNull(
			$this->fixture->getStatus()
		);
	}

	/**
	 * @test
	 */
	public function setStatusForIntegerSetsStatus() {
		$this->fixture->setStatus(3);
		$this->assertSame(
			3,
			$this->fixture->getStatus()
		);
	}

	/**
	 * @test
	 */
	public function getFrontendUserReturnsInitialValueForObject() { 
		$this->assertNull(
			$this->fixture->getFrontendUser()
		);
	}

	/**
	 * @test
	 */
	public function setFrontendUserForObjectSetsFrontendUser() { 
		$frontendUser = new \TYPO3\CMS\Extbase\Domain\Model\FrontendUser();
		$this->fixture->setFrontendUser($frontendUser);
		$this->assertSame(
			$frontendUser,
			$this->fixture->getFrontendUser()
		);
	}

	/**
	 * @test
	 */
	public function getOrderByReturnsInitialValueForString() {
		$this->assertNull($this->fixture->getOrder());
	}

	/**
	 * @test
	 */
	public function setOrderByForStringSetsOrderBy() { 
		$this->fixture->setOrderBy('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getOrderBy()
		);
	}
	
	/**
	 * @test
	 */
	public function getOrderByAllowedReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setOrderByAllowedForStringSetsDescription() { 
		$this->fixture->setOrderByAllowed('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getOrderByAllowed()
		);
	}
	
	/**
	 * @test
	 */
	public function getStoragePageReturnsInitialValueForInteger() {
		$this->assertNull(
			$this->fixture->getStoragePage()
		);
	}

	/**
	 * @test
	 */
	public function setStoragePageForIntegerSetsStoragePage() {
		$this->fixture->setStoragePage(123);
		$this->assertSame(
			123,
			$this->fixture->getStoragePage()
		);
	}
	
	/**
	 * @test
	 */
	public function getLimitReturnsInitialValueForInteger() {
		$this->assertNull(
			$this->fixture->getLimit()
		);
	}

	/**
	 * @test
	 */
	public function setLimitForIntegerSetsLimit() {
		$this->fixture->setLimit(999);
		$this->assertSame(
			999,
			$this->fixture->getLimit()
		);
	}
	
	/**
	 * @test
	 */
	public function getOffsetReturnsInitialValueForInteger() { }

	/**
	 * @test
	 */
	public function setOffsetForIntegerSetsOffset() { 
		$this->fixture->setOffset(300);

		$this->assertSame(
			300,
			$this->fixture->getOffset()
		);
	}
	
	/**
	 * @test
	 */
	public function getSearchReturnsInitialValueForSearch() {
		$this->assertNull($this->fixture->getSearch());
	}

	/**
	 * @test
	 */
	public function setSearchSetsSearch() {
		$search = new \Webfox\Placements\Domain\Model\Dto\Search;

		$this->fixture->setSearch($search);

		$this->assertSame(
			$search,
			$this->fixture->getSearch()
		);
	}
	
	/**
	 * @test
	 */
	public function getGeoLocationReturnsInitialValueForGeoLocation() {
		$this->assertNull($this->fixture->getGeoLocation());
	}

	/**
	 * @test
	 */
	public function setGeoLocationForArraySetsGeoLocation() {
		$array = array ('lat' => 0.5, 'lng' => 0.7);

		$this->fixture->setGeoLocation($array);
		$this->assertSame($array, $this->fixture->getGeoLocation());
	}

	/**
	 * @test
	 */
	public function getRadiusReturnsInitialValueForRadius () {
		$this->assertNull($this->fixture->getRadius());
	}

	/**
	 * @test
	 */
	public function setRadiusForIntegerSetsRadius () {
		$intVal = 12345;

		$this->fixture->setRadius($intVal);
		$this->assertSame($intVal, $this->fixture->getRadius());
	}
		
}

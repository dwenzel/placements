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
 * Test case for class \Webfox\Placements\Domain\Model\Position.
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
class PositionTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\Placements\Domain\Model\Position
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \Webfox\Placements\Domain\Model\Position();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getTitleReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setTitleForStringSetsTitle() { 
		$this->fixture->setTitle('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getTitle()
		);
	}
	
	/**
	 * @test
	 */
	public function getIdentifierReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setIdentifierForStringSetsIdentifier() { 
		$this->fixture->setIdentifier('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getIdentifier()
		);
	}
	
	/**
	 * @test
	 */
	public function getSummaryReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setSummaryForStringSetsSummary() { 
		$this->fixture->setSummary('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getSummary()
		);
	}
	
	/**
	 * @test
	 */
	public function getDescriptionReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setDescriptionForStringSetsDescription() { 
		$this->fixture->setDescription('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getDescription()
		);
	}
	
	/**
	 * @test
	 */
	public function getEntryDateReturnsInitialValueForDateTime() { }

	/**
	 * @test
	 */
	public function setEntryDateForDateTimeSetsEntryDate() { }
	
	/**
	 * @test
	 */
	public function getFixedTermReturnsInitialValueForOolean() { }

	/**
	 * @test
	 */
	public function setFixedTermForOoleanSetsFixedTerm() { }
	
	/**
	 * @test
	 */
	public function getDurationReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setDurationForStringSetsDuration() { 
		$this->fixture->setDuration('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getDuration()
		);
	}
	
	/**
	 * @test
	 */
	public function getZipReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setZipForStringSetsZip() { 
		$this->fixture->setZip('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getZip()
		);
	}
	
	/**
	 * @test
	 */
	public function getCityReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setCityForStringSetsCity() { 
		$this->fixture->setCity('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getCity()
		);
	}
	
	/**
	 * @test
	 */
	public function getLatitudeReturnsInitialValueForFloat() {
	    $this->assertNull($this->fixture->getLatitude());
	}
	
	/**
	 * @test
	 */
	public function setLatitudeForFloatSetsLatitude() {
	    $floatValue = 123.4567;
	    $this->fixture->setLatitude($floatValue);
	    $this->assertSame(
		    $floatValue,
		    $this->fixture->getLatitude()
		);
	}
	
	/**
	 * @test
	 */
	public function getLongitudeReturnsInitialValueForFloat() {
	    $this->assertNull($this->fixture->getLongitude());
	}
	
	/**
	 * @test
	 */
	public function setLongitudeForFloatSetsLongitude() {
	    $floatValue = 123.4567;
	    $this->fixture->setLongitude($floatValue);
	    $this->assertSame(
		    $floatValue,
		    $this->fixture->getLongitude()
		);
	}

	/**
	 * @test
	 */
	public function getPaymentReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setPaymentForStringSetsPayment() { 
		$this->fixture->setPayment('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getPayment()
		);
	}
	
	/**
	 * @test
	 */
	public function getContactReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setContactForStringSetsContact() { 
		$this->fixture->setContact('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getContact()
		);
	}
	
	/**
	 * @test
	 */
	public function getLinkReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setLinkForStringSetsLink() { 
		$this->fixture->setLink('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getLink()
		);
	}
	
	/**
	 * @test
	 */
	public function getOrganizationReturnsInitialValueForOrganization() { }

	/**
	 * @test
	 */
	public function setOrganizationForOrganizationSetsOrganization() { }
	
	/**
	 * @test
	 */
	public function getClientReturnsInitialValueForClient() { }

	/**
	 * @test
	 */
	public function setClientForClientSetsClient() { }
	
	/**
	 * @test
	 */
	public function getTypeReturnsInitialValueForPositionType() { }

	/**
	 * @test
	 */
	public function setTypeForPositionTypeSetsType() { }
	
	/**
	 * @test
	 */
	public function getCategoriesReturnsInitialValueForCategory() { 
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->fixture->getCategories()
		);
	}

	/**
	 * @test
	 */
	public function setCategoriesForObjectStorageContainingCategorySetsCategories() { 
		$category = new \TYPO3\CMS\Extbase\Domain\Model\Category();
		$objectStorageHoldingExactlyOneCategories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneCategories->attach($category);
		$this->fixture->setCategories($objectStorageHoldingExactlyOneCategories);

		$this->assertSame(
			$objectStorageHoldingExactlyOneCategories,
			$this->fixture->getCategories()
		);
	}
	
	/**
	 * @test
	 */
	public function addCategoryToObjectStorageHoldingCategories() {
		$category = new \TYPO3\CMS\Extbase\Domain\Model\Category();
		$objectStorageHoldingExactlyOneCategory = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneCategory->attach($category);
		$this->fixture->addCategory($category);

		$this->assertEquals(
			$objectStorageHoldingExactlyOneCategory,
			$this->fixture->getCategories()
		);
	}

	/**
	 * @test
	 */
	public function removeCategoryFromObjectStorageHoldingCategories() {
		$category = new \TYPO3\CMS\Extbase\Domain\Model\Category();
		$localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$localObjectStorage->attach($category);
		$localObjectStorage->detach($category);
		$this->fixture->addCategory($category);
		$this->fixture->removeCategory($category);

		$this->assertEquals(
			$localObjectStorage,
			$this->fixture->getCategories()
		);
	}
	
	/**
	 * @test
	 */
	public function getWorkingHoursReturnsInitialValueForWorkingHours() { }

	/**
	 * @test
	 */
	public function setWorkingHoursForWorkingHoursSetsWorkingHours() { }
	
	/**
	 * @test
	 */
	public function getSectorsReturnsInitialValueForSector() { 
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->fixture->getSectors()
		);
	}

	/**
	 * @test
	 */
	public function setSectorsForObjectStorageContainingSectorSetsSectors() { 
		$sector = new \Webfox\Placements\Domain\Model\Sector();
		$objectStorageHoldingExactlyOneSectors = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneSectors->attach($sector);
		$this->fixture->setSectors($objectStorageHoldingExactlyOneSectors);

		$this->assertSame(
			$objectStorageHoldingExactlyOneSectors,
			$this->fixture->getSectors()
		);
	}
	
	/**
	 * @test
	 */
	public function addSectorToObjectStorageHoldingSectors() {
		$sector = new \Webfox\Placements\Domain\Model\Sector();
		$objectStorageHoldingExactlyOneSector = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneSector->attach($sector);
		$this->fixture->addSector($sector);

		$this->assertEquals(
			$objectStorageHoldingExactlyOneSector,
			$this->fixture->getSectors()
		);
	}

	/**
	 * @test
	 */
	public function removeSectorFromObjectStorageHoldingSectors() {
		$sector = new \Webfox\Placements\Domain\Model\Sector();
		$localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$localObjectStorage->attach($sector);
		$localObjectStorage->detach($sector);
		$this->fixture->addSector($sector);
		$this->fixture->removeSector($sector);

		$this->assertEquals(
			$localObjectStorage,
			$this->fixture->getSectors()
		);
	}
	
}
?>

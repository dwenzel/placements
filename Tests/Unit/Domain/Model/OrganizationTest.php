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
 * Test case for class \Webfox\Placements\Domain\Model\Organization.
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
class OrganizationTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\Placements\Domain\Model\Organization
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \Webfox\Placements\Domain\Model\Organization();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getTitleReturnsInitialValueForString() { 
		$this->assertNull(
			$this->fixture->getTitle()
		);
	}

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
	public function getIdentifierReturnsInitialValueForString() {
		$this->assertNull(
			$this->fixture->getIdentifier()
		);
	}

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
	public function getDescriptionReturnsInitialValueForString() {
		$this->assertNull(
			$this->fixture->getDescription()
		);
	}

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
	public function getImageReturnsInitialValueForFileReference() {
		$this->assertNull(
			$this->fixture->getImage()
		);
	}

	/**
	 * @test
	 */
	public function setImageForFileReferenceSetsImage() {
		$newImage = $this->getMock(
				'Webfox\\Placements\\Domain\\Model\\FileReference',
				array(), array(), '', FALSE);
		$this->fixture->setImage($newImage);

		$this->assertSame(
			$newImage,
			$this->fixture->getImage()
		);
	}
	
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

	/**
	 * @test
	 */
	public function getClientReturnsInitialValueForClient() {
		$this->assertNull(
			$this->fixture->getClient()
		);
	}

	/**
	 * @test
	 */
	public function setClientForClientsSetsClient() {
		$client = new \Webfox\Placements\Domain\Model\Client();
		$this->fixture->setClient($client);

		$this->assertSame(
			$client,
			$this->fixture->getClient()
		);
	}

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
		$category = new \Webfox\Placements\Domain\Model\Category();
		$objectStorageHoldingExactlyOneCategory = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneCategory->attach($category);
		$this->fixture->setCategories($objectStorageHoldingExactlyOneCategory);

		$this->assertSame(
			$objectStorageHoldingExactlyOneCategory,
			$this->fixture->getCategories()
		);
	}
	
	/**
	 * @test
	 */
	public function addCategoryToObjectStorageHoldingCategories() {
		$category = new \Webfox\Placements\Domain\Model\Category();
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
		$category = new \Webfox\Placements\Domain\Model\Category();
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
}

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
 * Test case for class \Webfox\Placements\Domain\Model\Resume.
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
class ResumeTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\Placements\Domain\Model\Resume
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \Webfox\Placements\Domain\Model\Resume();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getSectionsReturnsInitialValueForSection() { 
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\Generic\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->fixture->getSections()
		);
	}

	/**
	 * @test
	 */
	public function setSectionsForObjectStorageContainingSectionSetsSections() { 
		$section = new \Webfox\Placements\Domain\Model\Section();
		$objectStorageHoldingExactlyOneSections = new \TYPO3\CMS\Extbase\Persistence\Generic\ObjectStorage();
		$objectStorageHoldingExactlyOneSections->attach($section);
		$this->fixture->setSections($objectStorageHoldingExactlyOneSections);

		$this->assertSame(
			$objectStorageHoldingExactlyOneSections,
			$this->fixture->getSections()
		);
	}
	
	/**
	 * @test
	 */
	public function addSectionToObjectStorageHoldingSections() {
		$section = new \Webfox\Placements\Domain\Model\Section();
		$objectStorageHoldingExactlyOneSection = new \TYPO3\CMS\Extbase\Persistence\Generic\ObjectStorage();
		$objectStorageHoldingExactlyOneSection->attach($section);
		$this->fixture->addSection($section);

		$this->assertEquals(
			$objectStorageHoldingExactlyOneSection,
			$this->fixture->getSections()
		);
	}

	/**
	 * @test
	 */
	public function removeSectionFromObjectStorageHoldingSections() {
		$section = new \Webfox\Placements\Domain\Model\Section();
		$localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\Generic\ObjectStorage();
		$localObjectStorage->attach($section);
		$localObjectStorage->detach($section);
		$this->fixture->addSection($section);
		$this->fixture->removeSection($section);

		$this->assertEquals(
			$localObjectStorage,
			$this->fixture->getSections()
		);
	}
	
}
?>
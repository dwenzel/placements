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
	public function getIntroductionReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setIntroductionForStringSetsIntroduction() { 
		$this->fixture->setIntroduction('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getIntroduction()
		);
	}
	
	/**
	 * @test
	 */
	public function getSectionsReturnsInitialValueForSection() { 
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
		$objectStorageHoldingExactlyOneSections = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
		$objectStorageHoldingExactlyOneSection = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
		$localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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

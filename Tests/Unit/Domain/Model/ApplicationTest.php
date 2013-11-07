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
 * Test case for class \Webfox\Placements\Domain\Model\Application.
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
class ApplicationTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\Placements\Domain\Model\Application
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \Webfox\Placements\Domain\Model\Application();
	}

	public function tearDown() {
		unset($this->fixture);
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
	public function getTextReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setTextForStringSetsText() { 
		$this->fixture->setText('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getText()
		);
	}
	
	/**
	 * @test
	 */
	public function getFileReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setFileForStringSetsFile() { 
		$this->fixture->setFile('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getFile()
		);
	}
	
	/**
	 * @test
	 */
	public function getPositionReturnsInitialValueForPosition() { }

	/**
	 * @test
	 */
	public function setPositionForPositionSetsPosition() { }
	
	/**
	 * @test
	 */
	public function getResumeReturnsInitialValueForResume() { 
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->fixture->getResume()
		);
	}

	/**
	 * @test
	 */
	public function setResumeForObjectStorageContainingResumeSetsResume() { 
		$resume = new \Webfox\Placements\Domain\Model\Resume();
		$objectStorageHoldingExactlyOneResume = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneResume->attach($resume);
		$this->fixture->setResume($objectStorageHoldingExactlyOneResume);

		$this->assertSame(
			$objectStorageHoldingExactlyOneResume,
			$this->fixture->getResume()
		);
	}
	
	/**
	 * @test
	 */
	public function addResumeToObjectStorageHoldingResume() {
		$resume = new \Webfox\Placements\Domain\Model\Resume();
		$objectStorageHoldingExactlyOneResume = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneResume->attach($resume);
		$this->fixture->addResume($resume);

		$this->assertEquals(
			$objectStorageHoldingExactlyOneResume,
			$this->fixture->getResume()
		);
	}

	/**
	 * @test
	 */
	public function removeResumeFromObjectStorageHoldingResume() {
		$resume = new \Webfox\Placements\Domain\Model\Resume();
		$localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$localObjectStorage->attach($resume);
		$localObjectStorage->detach($resume);
		$this->fixture->addResume($resume);
		$this->fixture->removeResume($resume);

		$this->assertEquals(
			$localObjectStorage,
			$this->fixture->getResume()
		);
	}
	
}
?>

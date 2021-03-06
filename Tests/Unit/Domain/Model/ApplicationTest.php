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
	public function getIntroductionReturnsInitialValueForString() {
		$this->assertNull($this->fixture->getIntroduction());
	}

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
	public function getTextReturnsInitialValueForString() {
		$this->assertNull($this->fixture->getText());
	}

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
	public function getFileReturnsInitialValueForString() {
		$this->assertNull($this->fixture->getFile());
	}

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
	public function getPositionReturnsInitialValueForPosition() {
		$this->assertNull($this->fixture->getPosition());
	}

	/**
	 * @test
	 */
	public function setPositionForPositionSetsPosition() {
		$position = new \Webfox\Placements\Domain\Model\Position();
		$this->fixture->setPosition($position);
		$this->assertSame(
			$position, 
			$this->fixture->getPosition()
		);
	}
	
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

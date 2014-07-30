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
 * Test case for class \Webfox\Placements\Domain\Model\Section.
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
class SectionTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\Placements\Domain\Model\Section
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \Webfox\Placements\Domain\Model\Section();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getBeginReturnsInitialValueForDateTime() {
		$this->assertNull(
			$this->fixture->getBegin()
		);
	}

	/**
	 * @test
	 */
	public function setBeginForDateTimeSetsBegin() {
		$date = new \DateTime('NOW');
		$this->fixture->setBegin($date);
		
		$this->assertSame(
			$date,
			$this->fixture->getBegin()
		);
	}
	
	/**
	 * @test
	 */
	public function getEndReturnsInitialValueForDateTime() {
		$this->assertNull(
			$this->fixture->getEnd()
		);
	}

	/**
	 * @test
	 */
	public function setEndForDateTimeSetsEnd() {
		$date = new \DateTime('NOW');
		$this->fixture->setEnd($date);
		
		$this->assertSame(
			$date,
			$this->fixture->getEnd()
		);
	}
	
	/**
	 * @test
	 */
	public function getPositionReturnsInitialValueForString() {
		$this->assertNull(
			$this->fixture->getPosition()
		);
	}

	/**
	 * @test
	 */
	public function setPositionForStringSetsPosition() { 
		$this->fixture->setPosition('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getPosition()
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
	
}

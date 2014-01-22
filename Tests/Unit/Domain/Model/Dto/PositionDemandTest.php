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
class PositionDemandTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\Placements\Domain\Model\Dto\PositionDemand
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \Webfox\Placements\Domain\Model\Dto\PositionDemand();
	}

	public function tearDown() {
		unset($this->fixture);
	}
	
	/**
	 * @test
	 */
	public function getPositionTypesReturnsInitialValueForString() {
		$this->assertNull(
			$this->fixture->getPositionTypes()
		);
	}

	/**
	 * @test
	 */
	public function setPostionTypesForStringSetsPositionTypes() { 
		$this->fixture->setPositionTypes('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getPositionTypes()
		);
	}
	
	/**
	 * @test
	 */
	public function getWorkingHoursReturnsInitialValueForString() {
		$this->assertNull(
			$this->fixture->getWorkingHours()
		);
	}

	/**
	 * @test
	 */
	public function setWorkingHoursForStringSetsWorkingHours() { 
		$this->fixture->setWorkingHours('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getWorkingHours()
		);
	}
	
	/**
	 * @test
	 */
	public function getSectorsReturnsInitialValueForString() {
		$this->assertNull(
			$this->fixture->getSectors()
		);
	}

	/**
	 * @test
	 */
	public function setSectorsForStringSetsSectors() { 
		$this->fixture->setSectors('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getSectors()
		);
	}
	
	/**
	 * @test
	 */
	public function getConstraintsConjunctionReturnsInitialValueForString() {
		$this->assertNull(
			$this->fixture->getConstraintsConjunction()
		);
	}

	/**
	 * @test
	 */
	public function setConstraintsConjunctionForStringSetsConstraintsConjunction() {
		$this->fixture->setConstraintsConjunction('asc');

		$this->assertSame(
			'asc',
			$this->fixture->getConstraintsConjunction()
		);
	}
	
	/**
	 * @test
	 */
	public function getCategoriesReturnsInitialValueForString() {
		$this->assertNull($this->fixture->getCategories());
	}

	/**
	 * @test
	 */
	public function setCategoriesForStringSetsCategories() { 
		$this->fixture->setCategories('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getCategories()
		);
	}
	
	/**
	 * @test
	 */
	public function getCategoryConjunctionReturnsInitialValueForString() {
		$this->assertNull(
			$this->fixture->getCategoryConjunction()
		);
	}

	/**
	 * @test
	 */
	public function setCategoryConjunctionForStringSetsCategoryConjunction() {
		$this->fixture->setCategoryConjunction('asc');

		$this->assertSame(
			'asc',
			$this->fixture->getCategoryConjunction()
		);
	}

	/**
	 * @test
	 */
	public function getClientsReturnsInitialValueForString() {
		$this->assertNull(
			$this->fixture->getClients()
		);
	}

	/**
	 * @test
	 */
	public function setClientsForStringSetsClients() {
		$this->fixture->setClients('1,2,3,4');

		$this->assertSame(
			'1,2,3,4',
			$this->fixture->getClients()
		);
	}

	/**
	 * @test
	 */
	public function getClientsPositionsOnlyReturnsInitialValueForBoolean() {
		$this->assertNull(
			$this->fixture->getClientsPositionsOnly()
		);
	}

	/**
	 * @test
	 */
	public function setClientsPositionsOnlyForBooleanSetsClientsPositionsOnly() {
		$this->fixture->setClientsPositionsOnly(TRUE);

		$this->assertSame(
			TRUE,
			$this->fixture->getClientsPositionsOnly()
		);
	}

}
?>

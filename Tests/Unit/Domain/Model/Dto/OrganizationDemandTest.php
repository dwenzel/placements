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
 * Test case for class \Webfox\Placements\Domain\Model\Dto\OrganizationDemand.
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
class OrganizationDemandTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\Placements\Domain\Model\Dto\OrganizationDemand
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \Webfox\Placements\Domain\Model\Dto\OrganizationDemand();
	}

	public function tearDown() {
		unset($this->fixture);
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
	public function getClientsOrganizationsOnlyReturnsInitialValueForBoolean() {
		$this->assertNull(
			$this->fixture->getClientsOrganizationsOnly()
		);
	}

	/**
	 * @test
	 */
	public function setClientsOrganizationsOnlyForBooleanSetsClientsOrganizationsOnly() {
		$this->fixture->setClientsOrganizationsOnly(TRUE);

		$this->assertSame(
			TRUE,
			$this->fixture->getClientsOrganizationsOnly()
		);
	}

}

<?php

namespace Webfox\Placements\Tests;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Dirk Wenzel <wenzel@webfox01.de>
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
 * Test case for class Webfox\Placements\Domain\Repository\OrganizationRepository.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Placements
 *
 * @author Dirk Wenzel <wenzel@webfox01.de>
 */
class OrganizationRepositoryTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\Placements\Domain\Repository\OrganizationRepository
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\Placements\\Domain\\Repository\\OrganizationRepository',
			array('dummy'), array(), '', FALSE);
		$this->fixture->_set('objectManager',
				$this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManagerInterface'));
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function createConstraintsFromDemandReturnsInitiallyEmptyConstraint() {
		$mockQuery = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query',
				array(), array(), '', FALSE);
		$mockDemand = $this->getMock('Webfox\Placements\Domain\Model\Dto\OrganizationDemand');
		$this->assertSame(
			array(null),
			$this->fixture->_call('createConstraintsFromDemand', $mockQuery, $mockDemand)
		);
	}

	/**
	 * @test
	 */
	public function createConstraintsFromDemandCreatesClientsConstraints() {
		$query = $this->getMock('\TYPO3\CMS\Extbase\Persistence\QueryInterface');
		$query->expects($this->once())->method('equals')->with('client.uid', 1);

		$demand = new \Webfox\Placements\Domain\Model\Dto\OrganizationDemand();
		$demand->setClients('1');

		$this->fixture->_call('createConstraintsFromDemand',$query, $demand);

	}
	/**
	 * @test
	 */
	public function dummy() {
		$this->markTestIncomplete();	
	}
}
?>
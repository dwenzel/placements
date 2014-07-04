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
 * Test case for class Webfox\Placements\Controller\OrganizationController.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Ajax Map
 *
 * @author Dirk Wenzel <wenzel@webfox01.de>
 */
class OrganizationControllerTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\Placements\Controller\OrganizationController
	 */
	protected $fixture;

	public function setUp() {
		$objectManager = new \TYPO3\CMS\Extbase\Object\ObjectManager();
	//		$this->fixture = $objectManager->get('Webfox\Placements\Controller\OrganizationController');
		$this->fixture = new \Webfox\Placements\Controller\OrganizationController();
		$this->organizationRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\OrganizationRepository', array(), array(), '', FALSE
		);
		$this->fixture->injectObjectManager($objectManager);
		$this->fixture->injectOrganizationRepository($this->organizationRepository);
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function createDemandFromSettingsCreatesEmptyDemandFromInvalidSettings() {
		$settings = array(
			'foo' => 'bar'
		);
		$demand = new \Webfox\Placements\Domain\Model\Dto\OrganizationDemand();
		
		$this->assertEquals(
			$this->fixture->createDemandFromSettings($settings),
			$demand
		);
	}

	/**
	 * @test
	 */
	public function createDemandFromSettingsCreatesDemandFromValidSettings() {
		$settings = array(
			'sectors' => 'foo',
			'categories' => '1,2,3',
			'clients' => '1,2,3',
			'clientsOrganizationsOnly' => TRUE,
			'orderBy' => 'bar',
			'orderDirection' => 'foo',
			'constraintsConjunction' => 'AND',
			'categoryConjunction' => 'NOR',
			'limit' => 5
		);
		$demand = new \Webfox\Placements\Domain\Model\Dto\OrganizationDemand();
		$demand->setSectors('foo');
		$demand->setCategories('1,2,3');
		$demand->setClients('1,2,3');
		$demand->setClientsOrganizationsOnly(TRUE);
		$demand->setOrder('bar|foo');
		$demand->setConstraintsConjunction('AND');
		$demand->setCategoryConjunction('NOR');
		$demand->setLimit(5);

		$this->assertEquals(
			$this->fixture->createDemandFromSettings($settings),
			$demand
		);
	}

}
?>

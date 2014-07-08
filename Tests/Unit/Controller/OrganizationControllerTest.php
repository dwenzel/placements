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
		//$objectManager = new \TYPO3\CMS\Extbase\Object\ObjectManager();
		$objectManager = $this->getMock('\\TYPO3\\CMS\\Extbase\\Object\\ObjectManager', array(), array(), '', FALSE);
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\Placements\\Controller\\OrganizationController', array('dummy'), array(), '', FALSE);
		$this->organizationRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\OrganizationRepository', array(), array(), '', FALSE
		);
		$accessControlService = $this->getMock(
				'Webfox\\Placements\\Service\\AccessControlService',
				array(), array(), '', FALSE);
		$this->fixture->_set('accessControlService', $accessControlService);
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
		$mockDemand = $this->getMock(
				'\Webfox\Placements\Domain\Model\Dto\OrganizationDemand');
		$this->fixture->_get('objectManager')->expects($this->once())
			->method('get')
			->with('Webfox\\Placements\\Domain\\Model\\Dto\\OrganizationDemand')
			->will($this->returnValue($mockDemand));
		
		$this->assertEquals(
			$this->fixture->createDemandFromSettings($settings),
			$mockDemand
		);
	}

	/**
	 * @test
	 */
	public function createDemandFromSettingsCreatesDemandFromSettings() {
		$settings = array(
			'notSettableProperty' => 'foo',
			'clientsOrganizationsOnly' => TRUE,
			'orderBy' => 'bar',
			'orderDirection' => 'foo',
			'constraintsConjunction' => 'AND'
		);
		$mockDemand = $this->getAccessibleMock(
				'\Webfox\Placements\Domain\Model\Dto\OrganizationDemand');
		$this->fixture->_get('objectManager')->expects($this->once())
			->method('get')
			->with('Webfox\\Placements\\Domain\\Model\\Dto\\OrganizationDemand')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())->method('setOrder')->with('bar|foo');
		$mockDemand->expects($this->exactly(2))->method('setClientsOrganizationsOnly')->with(TRUE);
		$mockDemand->expects($this->once())->method('setConstraintsConjunction')->with('AND');
		$mockDemand->expects($this->never())->method('setNotSettableProperty');
		$this->fixture->createDemandFromSettings($settings);
	}
	
	/**
	 * @test
	 */
	public function createDemandFromSettingsSetsClientsToEmptyStringIfNoClientLoggedIn() {
		$settings = array(
			'clientsOrganizationsOnly' => TRUE,
		);
		$mockDemand = $this->getMock('Webfox\Placements\Domain\Model\Dto\OrganizationDemand');
		$this->fixture->_get('objectManager')->expects($this->once())
			->method('get')
			->with('Webfox\\Placements\\Domain\\Model\\Dto\\OrganizationDemand')
			->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->exactly(2))->method('setClientsOrganizationsOnly')->with(TRUE);
		$this->fixture->_get('accessControlService')
			->expects($this->once())
			->method('hasLoggedInClient')
			->will($this->returnValue(FALSE));
		$mockDemand->expects($this->once())
			->method('setClients')
			->with('');
		$this->fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 */
	public function createDemandFromSettingSetsClientsForStringIfClientIsLoggedIn() {
		$settings = array(
			'clientsOrganizationsOnly' => TRUE
		);
		$mockDemand = $this->getMock('Webfox\Placements\Domain\Model\Dto\OrganizationDemand');
		$mockUser = $this->getMock('Webfox\Placements\Domain\Model\User');
		$mockClient = $this->getMock('Webfox\Placements\Domain\Model\Client');
		$this->fixture->_get('objectManager')->expects($this->once())
			->method('get')
			->will($this->returnValue($mockDemand));
		$this->fixture->_get('accessControlService')
			->expects($this->once())
			->method('hasLoggedInClient')
			->will($this->returnValue(TRUE));
		$this->fixture->_get('accessControlService')
			->expects($this->once())
			->method('getFrontendUser')
			->will($this->returnValue($mockUser));
		$mockUser->expects($this->once())
			->method('getClient')
			->will($this->returnValue($mockClient));
		$mockClient->expects($this->once())
			->method('getUid')
			->will($this->returnValue(5));
		$mockDemand->expects($this->once())
			->method('setClients')
			->with('5');
		$this->fixture->createDemandFromSettings($settings);
	}
}
?>

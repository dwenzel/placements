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
		$objectManager = $this->getMock('\\TYPO3\\CMS\\Extbase\\Object\\ObjectManager', array(), array(), '', FALSE);
		$configurationManager = $this->getMock(
				'TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface');
		$view = $this->getMock('TYPO3\\CMS\\Fluid\\View\\TemplateView', array(), array(), '', FALSE);
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\Placements\\Controller\\OrganizationController', array('dummy'), array(), '', FALSE);
		$organizationRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\OrganizationRepository', array(), array(), '', FALSE
		);
		$sectorRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\SectorRepository', array(), array(), '', FALSE
		);
		$categoryRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\CategoryRepository', array(), array(), '', FALSE
		);
		$flashMessageContainer = $this->getMock(
				'\TYPO3\CMS\Extbase\Mvc\Controller\FlashMessageContainer', array(), array(), '', FALSE
		);
		$controllerContext = $this->getMock(
				'\TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext', array(), array(), '', FALSE
		);
		$accessControlService = $this->getMock(
				'Webfox\\Placements\\Service\\AccessControlService',
				array(), array(), '', FALSE);
		$this->fixture->_set('accessControlService', $accessControlService);
		$this->fixture->_set('objectManager', $objectManager);
		$this->fixture->_set('organizationRepository', $organizationRepository);
		$this->fixture->_set('sectorRepository', $sectorRepository);
		$this->fixture->_set('categoryRepository', $categoryRepository);
		$this->fixture->_set('configurationManager', $configurationManager);
		$this->fixture->_set('flashMessageContainer', $flashMessageContainer);
		$this->fixture->_set('controllerContext', $controllerContext);
		$this->fixture->_set('view',$view);
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

	/**
	 * @test
	 */
	public function listActionCallsFindDemandedAndAssignsVariables() {
		$settings = array(
			'foo' => 'bar',
			'categories' => '15,3'
		);
		$mockResult = $this->getMock(
				'\TYPO3\CMS\Extbase\Persistence\Generic\QueryResult',
				array(), array(), '', FALSE);
		$this->fixture->_set('settings', $settings);
		$demand = new \Webfox\Placements\Domain\Model\Dto\OrganizationDemand();
		$mockDemand = $this->getMock('\Webfox\Placements\Domain\Model\Dto\OrganizationDemand');
		$this->fixture->_get('objectManager')->expects($this->once())
			->method('get')->will($this->returnValue($mockDemand));
		$mockDemand->expects($this->once())
			->method('setCategories')
			->with('15,3');
		$this->fixture->_get('organizationRepository')->expects($this->once())
			->method('findDemanded')
			->with($mockDemand)
			->will($this->returnValue($mockResult));
		$this->fixture->_get('view')->expects($this->once())
			->method('assignMultiple')
			->with(
				array(
					'organizations' => $mockResult,
					'settings' => $settings
				));

		$this->fixture->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsVariablesToView() {
		$settings = array(
			'foo' => 'bar'
		);
		$mockOrganization = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');
		$this->fixture->_set('settings', $settings);
		$this->fixture->_get('view')->expects($this->once())
			->method('assignMultiple')
			->with(
				array(
					'organization' => $mockOrganization,
					'settings' => $settings
				));

		$this->fixture->showAction($mockOrganization);
	}

	/**
	 * @test
	 */
	public function newActionAssignsVariablesToView() {
		$settings = array(
			'sectors' => '1,5,6',
			'categories' => '12,7,9'
		);
		$mockResult = $this->getMock(
				'\TYPO3\CMS\Extbase\Persistence\Generic\QueryResult',
				array(), array(), '', FALSE);
		$this->fixture->_set('settings', $settings);
		$this->fixture->_get('sectorRepository')->expects($this->once())
			->method('findMultipleByUid')
			->with('1,5,6')
			->will($this->returnValue($mockResult));
		$this->fixture->_get('categoryRepository')->expects($this->once())
			->method('findMultipleByUid')
			->with('12,7,9')
			->will($this->returnValue($mockResult));
		$this->fixture->_get('view')->expects($this->once())
			->method('assignMultiple')
			->with(
				array(
					'newOrganization' => NULL,
					'sectors' => $mockResult,
					'categories' => $mockResult,
					'settings' => $settings
				));

		$this->fixture->newAction();
	}

	/**
	 * @test
	 */
	public function editActionAssignsVariablesToView() {
		$settings = array(
			'sectors' => '1,5,6',
			'categories' => '12,7,9'
		);
		$mockOrganization = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');
		$mockResult = $this->getMock(
				'\TYPO3\CMS\Extbase\Persistence\Generic\QueryResult',
				array(), array(), '', FALSE);
		$this->fixture->_set('settings', $settings);
		$this->fixture->_get('sectorRepository')->expects($this->once())
			->method('findMultipleByUid')
			->with('1,5,6')
			->will($this->returnValue($mockResult));
		$this->fixture->_get('categoryRepository')->expects($this->once())
			->method('findMultipleByUid')
			->with('12,7,9')
			->will($this->returnValue($mockResult));
		$this->fixture->_get('view')->expects($this->once())
			->method('assignMultiple')
			->with(
				array(
					'organization' => $mockOrganization,
					'sectors' => $mockResult,
					'categories' => $mockResult,
					'settings' => $settings
				));

		$this->fixture->editAction($mockOrganization);
	}

	/**
	 * @test
	 * @expectedException \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
	 */
	public function deleteActionRemovesOrganization() {
		$mockOrganization = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');
		$mockMessageQueue = $this->getMock(
			'\TYPO3\CMS\Core\Messaging\FlashMessageQueue', array(), array(), '', FALSE);
		$this->fixture->_get('organizationRepository')->expects($this->once())
			->method('remove')
			->with($mockOrganization);
		$this->fixture->_get('controllerContext')->expects($this->once())
			->method('getFlashMessageQueue')
			->will($this->returnValue($mockMessageQueue));

		$this->fixture->deleteAction($mockOrganization);
	}

	/**
	 * @test
	 * @expectedException \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
	 */
	public function createActionCreatesOrganization() {
		$mockOrganization = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');
		$mockRequest = $this->getMock(
				$this->buildAccessibleProxy('TYPO3\CMS\Extbase\MVC\Request'), array('dummy'), array(), '', FALSE);
		$mockRequest->_set('pluginName', 'Placements');
		$mockRequest->_set('controllerName', 'OrganisationController');
		$mockRequest->_set('arguments', array(
					'newOrganization' => $mockOrganisation,
					'save-reload' => TRUE));
		$this->fixture->_set('request', $mockRequest);
		$mockPersistenceManager = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager', array(), array(), '', FALSE);
		$this->fixture->_get('objectManager')->expects($this->once())
			->method('get')
			->with('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')
			->will($this->returnValue($mockPersistenceManager));
		$mockUser = $this->getMock('Webfox\Placements\Domain\Model\User');
		$mockClient = $this->getMock('Webfox\Placements\Domain\Model\Client');
		$mockMessageQueue = $this->getMock(
			'\TYPO3\CMS\Core\Messaging\FlashMessageQueue', array(), array(), '', FALSE);
		$this->fixture->_get('accessControlService')->expects($this->once())
			->method('getFrontendUser')
			->will($this->returnValue($mockUser));
		$mockUser->expects($this->once())
			->method('getClient')
			->will($this->returnValue($mockClient));
		$this->fixture->_get('organizationRepository')->expects($this->once())
			->method('add')
			->with($mockOrganization);
		$this->fixture->_get('controllerContext')->expects($this->once())
			->method('getFlashMessageQueue')
			->will($this->returnValue($mockMessageQueue));

		$this->fixture->createAction($mockOrganization);
	}
}
?>

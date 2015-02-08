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
 * Test case for class Webfox\Placements\Controller\OrganizationController.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Ajax Map
 * @coversDefaultClass \Webfox\Placements\Controller\OrganizationController
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
			'Webfox\\Placements\\Controller\\OrganizationController', array('translate'), array(), '', FALSE);
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
	 * @covers ::initializeAction
	 */
	public function initializeActionSetsTargetTypeForSubPropertyImage() {
		$fixture = $this->getAccessibleMock(
			'\Webfox\Placements\Controller\OrganizationController',
			array('dummy'), array(), '', FALSE);
		$mockArguments = $this->getMock(
			'\TYPO3\CMS\Extbase\Mvc\Controller\Arguments',
			array(), array(), '', FALSE);
		$fixture->_set('arguments', $mockArguments);
		$mockArgument = $this->getMock(
			'\TYPO3\CMS\Extbase\Mvc\Controller\Argument',
			array('getPropertyMappingConfiguration'), array(), '', FALSE);
		$mockMappingConfiguration = $this->getMock(
			'\TYPO3\CMS\Extbase\Property\MappingConfiguration',
			array('setTargetTypeForSubProperty'), array(), '', FALSE);
		$mockArguments->expects($this->exactly(2))->method('hasArgument')
			->will($this->returnValue(TRUE));
		$mockArguments->expects($this->exactly(2))->method('getArgument')
			->will($this->returnValue($mockArgument));
		$mockArgument->expects($this->exactly(2))->method('getPropertyMappingConfiguration')
			->will($this->returnValue($mockMappingConfiguration));
		$mockMappingConfiguration->expects($this->exactly(2))->method('setTargetTypeForSubProperty')
			->with('image', 'array');

		$fixture->initializeAction();

	}

	/**
	 * @test
	 * @covers ::createDemandFromSettings
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
	 * @covers ::createDemandFromSettings
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
	 * @covers ::createDemandFromSettings
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
	 * @covers ::createDemandFromSettings
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
	 * @covers ::listAction
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
	 * @covers ::showAction
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
	 * @covers ::newAction
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
	 * @covers ::editAction
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
	 * @covers ::deleteAction
	 */
	public function deleteActionRemovesOrganization() {
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\OrganizationController',
			array('translate', 'addFlashMessage', 'redirect'), array(), '', FALSE);
		$mockOrganization = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');
		$mockAccessControlService = $this->getMock(
			'Webfox\Placements\Service\AccessControlService',
			array('isAllowedToDelete'), array(), '', FALSE);
		$mockPositionRepository =$this->getMock(
			'Webfox\Placements\Domain\Repository\PositionRepository',
			array('countByOrganization'), array(), '', FALSE);
		$fixture->_set('positionRepository', $mockPositionRepository);
		$fixture->_set('accessControlService', $mockAccessControlService);
		$mockOrganizationRepository =$this->getMock(
			'Webfox\Placements\Domain\Repository\OrganizationRepository',
			array('remove'), array(), '', FALSE);
		$fixture->_set('organizationRepository', $mockOrganizationRepository);

		$mockAccessControlService->expects($this->once())->method('isAllowedToDelete')
			->with('organization')
			->will($this->returnValue(TRUE));
		$mockPositionRepository->expects($this->once())->method('countByOrganization')
			->with($mockOrganization)
			->will($this->returnValue(FALSE));
		$mockOrganizationRepository->expects($this->once())
			->method('remove')
			->with($mockOrganization);
		$fixture->expects($this->once())->method('translate')
			->with('tx_placements.success.organization.deleteAction')
			->will($this->returnValue('foo'));
		$fixture->expects($this->once())->method('addFlashMessage')
			->with('foo');

		$fixture->deleteAction($mockOrganization);
	}

	/**
	 * @test
	 * @covers ::deleteAction
	 */
	public function deleteActionDoesNotDeleteOrganizationIfNotAllowed() {
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\OrganizationController',
			array('translate', 'addFlashMessage', 'redirect'), array(), '', FALSE);
		$mockAccessControlService = $this->getMock(
			'Webfox\Placements\Service\AccessControlService',
			array('isAllowedToDelete'), array(), '', FALSE);
		$fixture->_set('accessControlService', $mockAccessControlService);
		$mockOrganization = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');

		$mockAccessControlService->expects($this->once())->method('isAllowedToDelete')
			->with('organization');
		$fixture->expects($this->once())->method('translate')
			->with('tx_placements.error.organization.deleteActionNotAllowed')
			->will($this->returnValue('foo'));
		$fixture->expects($this->once())->method('addFlashMessage')
			->with('foo');

		$fixture->deleteAction($mockOrganization);
	}

	/**
	 * @test
	 * @covers ::deleteAction
	 */
	public function deleteActionDoesNotDeleteOrganizationIfReferencedByAnyPosition() {
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\OrganizationController',
			array('translate', 'addFlashMessage', 'redirect'), array(), '', FALSE);
		$mockAccessControlService = $this->getMock(
			'Webfox\Placements\Service\AccessControlService',
			array('isAllowedToDelete'), array(), '', FALSE);
		$fixture->_set('accessControlService', $mockAccessControlService);
		$mockPositionRepository =$this->getMock(
			'Webfox\Placements\Domain\Repository\PositionRepository',
			array('countByOrganization'), array(), '', FALSE);
		$fixture->_set('positionRepository', $mockPositionRepository);
		$mockOrganization = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');

		$mockAccessControlService->expects($this->once())->method('isAllowedToDelete')
			->with('organization')
			->will($this->returnValue(TRUE));
		$mockPositionRepository->expects($this->once())->method('countByOrganization')
			->will($this->returnValue(5));
		$fixture->expects($this->once())->method('translate')
			->with('tx_placements.error.organization.canNotDeleteOrganizationReferencedByPositions')
			->will($this->returnValue('foo'));
		$fixture->expects($this->once())->method('addFlashMessage')
			->with('foo');

		$fixture->deleteAction($mockOrganization);
	}

	/**
	 * @test
	 * @covers ::updateAction
	 */
	public function updateActionUpdatesOrganizationAndRedirectsToListView() {
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\OrganizationController',
			array('translate', 'addFlashMessage', 'redirect'),
			array(), '', FALSE);

		$mockRepository = $this->getMock(
			'Webfox\Placements\Domain\Repository\OrganizationRepository',
			array('update'), array(), '', FALSE);
		$fixture->_set('organizationRepository', $mockRepository);

		$mockOrganization = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');

		$mockRequest = $this->getMock(
				$this->buildAccessibleProxy('TYPO3\CMS\Extbase\MVC\Request'), array('hasArgument'), array(), '', FALSE);
		$fixture->_set('request', $mockRequest);

		$mockPersistenceManager = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager', array('persistAll'), array(), '', FALSE);
		$fixture->_set('persistenceManager', $mockPersistenceManager);

		$mockRepository->expects($this->once())
			->method('update')
			->with($mockOrganization);
		$fixture->expects($this->once())->method('translate')
			->with('tx_placements.success.organization.updateAction')
			->will($this->returnValue('foo'));
		$fixture->expects($this->once())->method('addFlashMessage')
			->with('foo');
		$mockRequest->expects($this->any())->method('hasArgument')
			->will($this->returnValue(FALSE));
		$fixture->expects($this->once())->method('redirect')
			->with('list');
		$fixture->updateAction($mockOrganization);
	}

	/**
	 * @test
	 * @covers ::updateAction
	 */
	public function updateActionRedirectsToShowViewForSaveView() {
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\OrganizationController',
			array('translate', 'addFlashMessage', 'redirect'),
			array(), '', FALSE);

		$mockRepository = $this->getMock(
			'Webfox\Placements\Domain\Repository\OrganizationRepository',
			array('update'), array(), '', FALSE);
		$fixture->_set('organizationRepository', $mockRepository);

		$mockOrganization = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');

		$mockRequest = $this->getMock(
				$this->buildAccessibleProxy('TYPO3\CMS\Extbase\MVC\Request'), array('hasArgument'), array(), '', FALSE);
		$fixture->_set('request', $mockRequest);

		$mockPersistenceManager = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager', array('persistAll'), array(), '', FALSE);
		$fixture->_set('persistenceManager', $mockPersistenceManager);

		$mockRepository->expects($this->once())
			->method('update')
			->with($mockOrganization);
		$fixture->expects($this->once())->method('translate')
			->with('tx_placements.success.organization.updateAction')
			->will($this->returnValue('foo'));
		$fixture->expects($this->once())->method('addFlashMessage')
			->with('foo');
		$mockRequest->expects($this->any())->method('hasArgument')
			->with('save-view')
			->will($this->returnValue(TRUE));
		$fixture->expects($this->once())->method('redirect')
			->with('show', NULL, NULL, array('organization' => $mockOrganization));
		$fixture->updateAction($mockOrganization);
	}

	/**
	 * @test
	 * @covers ::updateAction
	 */
	public function updateActionRedirectsToEditViewForSaveReload() {
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\OrganizationController',
			array('translate', 'addFlashMessage', 'redirect'),
			array(), '', FALSE);

		$mockRepository = $this->getMock(
			'Webfox\Placements\Domain\Repository\OrganizationRepository',
			array('update'), array(), '', FALSE);
		$fixture->_set('organizationRepository', $mockRepository);

		$mockOrganization = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');

		$mockRequest = $this->getMock(
				$this->buildAccessibleProxy('TYPO3\CMS\Extbase\MVC\Request'), array('hasArgument'), array(), '', FALSE);
		$fixture->_set('request', $mockRequest);

		$mockPersistenceManager = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager', array('persistAll'), array(), '', FALSE);
		$fixture->_set('persistenceManager', $mockPersistenceManager);

		$mockRepository->expects($this->once())
			->method('update')
			->with($mockOrganization);
		$fixture->expects($this->once())->method('translate')
			->with('tx_placements.success.organization.updateAction')
			->will($this->returnValue('foo'));
		$fixture->expects($this->once())->method('addFlashMessage')
			->with('foo');
		$mockRequest->expects($this->any())->method('hasArgument')
			->will($this->onConsecutiveCalls(FALSE, TRUE));
		$fixture->expects($this->once())->method('redirect')
			->with('edit', NULL, NULL, array('organization' => $mockOrganization));
		$fixture->updateAction($mockOrganization);
	}

	/**
	 * @test
	 * @covers ::createAction
	 */
	public function createActionCreatesOrganization() {
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\OrganizationController',
			array('translate', 'addFlashMessage', 'redirect'),
			array(), '', FALSE);

		$mockRepository = $this->getMock(
			'Webfox\Placements\Domain\Repository\OrganizationRepository',
			array('add'), array(), '', FALSE);
		$fixture->_set('organizationRepository', $mockRepository);

		$mockOrganization = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');

		$mockRequest = $this->getMock(
				$this->buildAccessibleProxy('TYPO3\CMS\Extbase\MVC\Request'), array('hasArgument'), array(), '', FALSE);
	/*	$mockRequest->_set('pluginName', 'Placements');
		$mockRequest->_set('controllerName', 'OrganizationController');
		$mockRequest->_set('arguments', array(
					'newOrganization' => $mockOrganisation,
					'save-reload' => TRUE));*/
		$fixture->_set('request', $mockRequest);

		$mockAccessControlService = $this->getMock(
			'Webfox\Placements\Service\AccessControlService',
			array('getFrontendUser'));
		$fixture->_set('accessControlService', $mockAccessControlService);

		$mockUser = $this->getMock('Webfox\Placements\Domain\Model\User');
		$mockClient = $this->getMock('Webfox\Placements\Domain\Model\Client');

		$mockAccessControlService->expects($this->once())
			->method('getFrontendUser')
			->will($this->returnValue($mockUser));
		$mockUser->expects($this->once())
			->method('getClient')
			->will($this->returnValue($mockClient));
		$mockRepository->expects($this->once())
			->method('add')
			->with($mockOrganization);
		$fixture->expects($this->once())->method('translate')
			->with('tx_placements.success.organization.createAction')
			->will($this->returnValue('foo'));
		$fixture->expects($this->once())->method('addFlashMessage')
			->with('foo');
		$mockRequest->expects($this->any())->method('hasArgument')
			->will($this->returnValue(FALSE));
		$fixture->expects($this->once())->method('redirect')
			->with('list');
		$fixture->createAction($mockOrganization);
	}

	/**
	 * @test
	 * @covers ::createAction
	 */
	public function createActionPersistsAllAndRedirectsForSaveReload() {
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\OrganizationController',
			array('translate', 'addFlashMessage', 'redirect'),
			array(), '', FALSE);

		$mockRepository = $this->getMock(
			'Webfox\Placements\Domain\Repository\OrganizationRepository',
			array('add'), array(), '', FALSE);
		$fixture->_set('organizationRepository', $mockRepository);

		$mockOrganization = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');

		$mockRequest = $this->getMock(
				$this->buildAccessibleProxy('TYPO3\CMS\Extbase\MVC\Request'), array('hasArgument'), array(), '', FALSE);
		$mockRequest->_set('pluginName', 'Placements');
		$mockRequest->_set('controllerName', 'OrganisationController');
		$fixture->_set('request', $mockRequest);
		$mockObjectManager = $this->getMock('TYPO3\CMS\Extbase\Object\ObjectManager',
				array('get'), array(), '', FALSE);
		$fixture->_set('objectManager', $mockObjectManager);

		$mockPersistenceManager = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager', array('persistAll'), array(), '', FALSE);

		$mockAccessControlService = $this->getMock(
			'Webfox\Placements\Service\AccessControlService',
			array('getFrontendUser'));
		$fixture->_set('accessControlService', $mockAccessControlService);

		$mockUser = $this->getMock('Webfox\Placements\Domain\Model\User');
		$mockClient = $this->getMock('Webfox\Placements\Domain\Model\Client');

		$mockAccessControlService->expects($this->once())
			->method('getFrontendUser')
			->will($this->returnValue($mockUser));
		$mockUser->expects($this->once())
			->method('getClient')
			->will($this->returnValue($mockClient));
		$mockRepository->expects($this->once())
			->method('add');
		$fixture->expects($this->once())->method('translate')
			->will($this->returnValue('foo'));
		$fixture->expects($this->once())->method('addFlashMessage');
		$mockRequest->expects($this->any())->method('hasArgument')
			->with('save-reload')
			->will($this->returnValue(TRUE));
		$mockObjectManager->expects($this->once())->method('get')
			->with('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')
			->will($this->returnValue($mockPersistenceManager));
		$mockPersistenceManager->expects($this->once())->method('persistAll');
		$fixture->expects($this->once())->method('redirect')
			->with('edit', NULL, NULL, array('organization' => $mockOrganization));
		$fixture->createAction($mockOrganization);
	}

	/**
	 * @test
	 * @covers ::createAction
	 */
	public function createActionRedirectsForSaveView() {
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\OrganizationController',
			array('translate', 'addFlashMessage', 'redirect'),
			array(), '', FALSE);

		$mockRepository = $this->getMock(
			'Webfox\Placements\Domain\Repository\OrganizationRepository',
			array('add'), array(), '', FALSE);
		$fixture->_set('organizationRepository', $mockRepository);

		$mockOrganization = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');

		$mockRequest = $this->getMock(
				$this->buildAccessibleProxy('TYPO3\CMS\Extbase\MVC\Request'), array('hasArgument'), array(), '', FALSE);
		$fixture->_set('request', $mockRequest);
		$mockObjectManager = $this->getMock('TYPO3\CMS\Extbase\Object\ObjectManager',
				array('get'), array(), '', FALSE);
		$fixture->_set('objectManager', $mockObjectManager);

		$mockPersistenceManager = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager', array('persistAll'), array(), '', FALSE);

		$mockAccessControlService = $this->getMock(
			'Webfox\Placements\Service\AccessControlService',
			array('getFrontendUser'));
		$fixture->_set('accessControlService', $mockAccessControlService);

		$mockUser = $this->getMock('Webfox\Placements\Domain\Model\User');
		$mockClient = $this->getMock('Webfox\Placements\Domain\Model\Client');

		$mockAccessControlService->expects($this->once())
			->method('getFrontendUser')
			->will($this->returnValue($mockUser));
		$mockUser->expects($this->once())
			->method('getClient')
			->will($this->returnValue($mockClient));
		$mockRepository->expects($this->once())
			->method('add');
		$fixture->expects($this->once())->method('translate')
			->will($this->returnValue('foo'));
		$fixture->expects($this->once())->method('addFlashMessage');
		$mockRequest->expects($this->any())->method('hasArgument')
			->will($this->onConsecutiveCalls(FALSE, TRUE, FALSE, TRUE));
		$mockObjectManager->expects($this->once())->method('get')
			->with('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')
			->will($this->returnValue($mockPersistenceManager));
		$mockPersistenceManager->expects($this->once())->method('persistAll');
		$fixture->expects($this->once())->method('redirect')
			->with('show', NULL, NULL, array('organization' => $mockOrganization));
		$fixture->createAction($mockOrganization);
	}

	/**
	 * @test
	 * @covers ::getErrorFlashMessage
	 */
	public function getErrorFlashMessageReturnsTranslatedErrorMessageForAction() {
		$fixture = $this->getAccessibleMock(
			'\Webfox\Placements\Controller\OrganizationController',
			array('translate'), array(), '', FALSE);
		$fixture->_set('actionMethodName', 'foo');

		$fixture->expects($this->once())->method('translate')
			->with('tx_placements.error.organization.foo');
		$fixture->_call('getErrorFlashMessage');
	}
}

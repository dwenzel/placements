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
 * Test case for class \Webfox\Placements\Controller\PositionController.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Placement Service
 *
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @coversDefaultClass \Webfox\Placements\Controller\PositionController
 */
class PositionControllerTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var 
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getAccessibleMock(
			'\Webfox\Placements\Controller\PositionController',
			array('dummy'), array(), '', FALSE);
		$positionRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\PositionRepository', array(), array(), '', FALSE
		);
		$view = $this->getMock('TYPO3\\CMS\\Fluid\\View\\TemplateView', array(), array(), '', FALSE);
		$flashMessageContainer = $this->getMock(
				'\TYPO3\CMS\Extbase\Mvc\Controller\FlashMessageContainer', array(), array(), '', FALSE
		);
		$controllerContext = $this->getMock(
				'\TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext', array(), array(), '', FALSE
		);
		$accessControlService = $this->getMock(
				'Webfox\\Placements\\Service\\AccessControlService',
				array(), array(), '', FALSE);
		$mockArguments = $this->getMock('TYPO3\CMS\Extbase\Mvc\Controller\Arguments');
		$objectManager = $this->getMock('\\TYPO3\\CMS\\Extbase\\Object\\ObjectManager', array(), array(), '', FALSE);
		$this->fixture->_set('positionRepository', $positionRepository);
		$this->fixture->_set('view',$view);
		$this->fixture->_set('configurationManager', $configurationManager);
		$this->fixture->_set('flashMessageContainer', $flashMessageContainer);
		$this->fixture->_set('controllerContext', $controllerContext);
		$this->fixture->_set('arguments', $mockArguments);
		$this->fixture->_set('objectManager', $objectManager);
		$this->fixture->_set('accessControlService', $accessControlService);
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 * @expectedException \TYPO3\CMS\Extbase\Property\Exception\TargetNotFoundException
	 */
	public function processRequestHandlesTargetNotFoundException() {
		$this->markTestSkipped();
		$fixture = $this->getMock('Webfox\Placements\Controller\PositionController',
				array());
		$mockPosition = $this->getMock(
			'Webfox\Placements\Domain\Model\Position');
		$mockRequest = $this->getMock(
				$this->buildAccessibleProxy('TYPO3\CMS\Extbase\MVC\Request'), array('dummy'), array(), '', FALSE);
		$mockRequest->_set('pluginName', 'Placements');
		$mockRequest->_set('controllerName', 'PositionController');
		$mockRequest->_set('controllerActionName', 'show');
		/*$mockRequest->_set('arguments', array(
					'position' => $mockPosition,));*/
		$this->fixture->_set('request', $mockRequest);
		
		$mockResponse = $this->getMock(
			'\TYPO3\CMS\Extbase\Mvc\ResponseInterface');
		$mockUriBuilder = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder');
/*
		$this->fixture->_get('objectManager')->expects($this->once())
			->method('get')
			->with('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder')
			->will($this->returnValue($mockUriBuilder));*/
		$this->fixture->expects($this->any())
			->method('processRequest')
			->will($this->throwException(new \TYPO3\CMS\Extbase\Property\Exception\TargetNotFoundException()));
		$this->fixture->expects($this->once())
			->method('handleEntityNotFoundError');
		$fixture->expects($this->once())
			->method('mapRequestArgumentsToControllerArguments');
		//$this->fixture->showAction($mockPosition);
		$this->fixture->processRequest($mockRequest, $mockResponse);
	}

	/**
	 * @test
	 */
	public function initializeAjaxShowActionSetsTypConverterForUid() {
		$mockArgument = $this->getMock(
			'TYPO3\CMS\Extbase\Mvc\Controller\Argument', array(), array(), '', FALSE);
		$mockMappingConfiguration = $this->getMock(
			'TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfiguration');
		$this->fixture->_get('objectManager')->expects($this->once())
			->method('get')
			->will($this->returnValue($this->getMock('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\StringConverter')));
		$this->fixture->_get('arguments')->expects($this->once())
			->method('hasArgument')
			->with('uid')
			->will($this->returnValue(TRUE));
		$this->fixture->_get(arguments)->expects($this->once())
			->method('getArgument')
			->with('uid')
			->will($this->returnValue($mockArgument));
		$mockArgument->expects($this->once())
			->method('getPropertyMappingConfiguration')
			->will($this->returnValue($mockMappingConfiguration));
		$mockMappingConfiguration->expects($this->once())
			->method('setTypeConverter');
		$this->fixture->initializeAjaxShowAction();
	}

	/**
	 * @test
	 */
	public function initializeAjaxListActionSetsTypConverterForOverwriteDemand() {
		$mockArgument = $this->getMock(
			'TYPO3\CMS\Extbase\Mvc\Controller\Argument', array(), array(), '', FALSE);
		$mockMappingConfiguration = $this->getMock(
			'TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfiguration');
		$mockConverter = $this->getMock('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\ArrayConverter');
		$this->fixture->_get('objectManager')->expects($this->once())
			->method('get')
			->will($this->returnValue($mockConverter));
		$this->fixture->_get('arguments')->expects($this->once())
			->method('hasArgument')
			->with('overwriteDemand')
			->will($this->returnValue(TRUE));
		$this->fixture->_get(arguments)->expects($this->once())
			->method('getArgument')
			->with('overwriteDemand')
			->will($this->returnValue($mockArgument));
		$mockArgument->expects($this->once())
			->method('getPropertyMappingConfiguration')
			->will($this->returnValue($mockMappingConfiguration));
		$mockMappingConfiguration->expects($this->once())
			->method('setTypeConverter')
			->with($mockConverter);
		$this->fixture->initializeAjaxListAction();
	}


	/**
	 * @test
	 */
	public function listActionCallsFindDemandedAndAssignsVariables() {
		$fixture = $this->getAccessibleMock(
				'Webfox\\Placements\\Controller\PositionController', 
				array('createDemandFromSettings', 'overwriteDemandObject', 'addFlashMessage', 'translate'), array(), '', FALSE);
		$fixture->_set('positionRepository', $this->getMock('Webfox\\Placements\\Domain\\Repository\\PositionRepository', array('findDemanded'), array(), '', FALSE));
		$overwriteDemand = array(
			'foo' => 'bar',
		);
		$settings = array('foo' => 'bar');
		$fixture->_set('settings', $settings);
		$fixture->_set('view', $this->getMock('TYPO3\CMS\Fluid\View\TemplateView', 
				array(), array(), '', FALSE));
		$mockResult = $this->getMock(
				'TYPO3\CMS\Extbase\Persistence\Generic\QueryResult',
				array(), array(), '', FALSE);
		$fixture->_set('settings', $settings);
		$mockDemand = $this->getMock('Webfox\\Placements\\Domain\\Model\\Dto\\PositionDemand', array(), array(), '', FALSE);
		$fixture->expects($this->once())
			->method('createDemandFromSettings')
			->with($settings)
			->will($this->returnValue($mockDemand));
		$fixture->expects($this->once())
			->method('overwriteDemandObject')
			->with($mockDemand, $overwriteDemand)
			->will($this->returnValue($mockDemand));
		$fixture->expects($this->once())->method('overwriteDemandObject')->will($this->returnValue($mockDemand));
		$fixture->_get('positionRepository')->expects($this->once())
			->method('findDemanded')
			->with($mockDemand)
			->will($this->returnValue($mockResult));
		$mockResult->expects($this->once())->method('count')->will($this->returnValue(0));
		$fixture->expects($this->once())
			->method('addFlashMessage')
			->with('foo');
		$fixture->expects($this->once())
			->method('translate')
			->with('tx_placements.list.position.message.noPositionFound')
			->will($this->returnValue('foo'));
		$fixture->_get('view')->expects($this->once())
			->method('assignMultiple')
			->with(
				array(
					'positions' => $mockResult,
					'overwriteDemand' => $overwriteDemand,
					'requestArguments' => null
				));

		$fixture->listAction($overwriteDemand);
	}

	/**
	 * @test
	 */
	public function ajaxListActionReturnsInitialliyEmptyResult() {
		$fixture = $this->getAccessibleMock('\Webfox\Placements\Controller\PositionController',
				array('createDemandFromSettings'), array(), '', FALSE);
		$fixture->_set('positionRepository', $this->getMock(
				'\Webfox\Placements\Domain\Repository\PositionRepository', array(), array(), '', FALSE));
		$mockDemand = $this->getMock('Webfox\Placements\Domain\Model\Dto\PositionDemand');
		$mockResult = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\QueryResult',
				array(), array(), '', FALSE);
		$fixture->expects($this->once())->method('createDemandFromSettings')
			->with(NULL)->will($this->returnValue($mockDemand));
		$fixture->_get('positionRepository')->expects($this->once())->method('findDemanded')->with($mockDemand, TRUE)
			->will($this->returnValue($mockResult));
		$mockResult->expects($this->once())->method('toArray')
			->will($this->returnValue(array()));
		$this->assertSame(
				'[]',
				$fixture->ajaxListAction()
		);

	}

	/**
	 * @test
	 */
	public function ajaxListActionReturnsCorrectResult() {
		$fixture = $this->getAccessibleMock('\Webfox\Placements\Controller\PositionController',
				array('createDemandFromSettings', 'overwriteDemandObject'), array(), '', FALSE);
		$fixture->_set('positionRepository', $this->getMock(
				'Webfox\\Placements\\Domain\\Repository\\PositionRepository', array('findDemanded'), array(), '', FALSE));
		$overwriteDemand = array('foo' => 'bar');
		$mockDemand = $this->getMock('\Webfox\Placements\Domain\Model\Dto\PositionDemand');
		$mockQueryResult = $this->getMock(
				'TYPO3\CMS\Extbase\Persistence\Generic\QueryResult',
				array(), array(), '', FALSE);
		$mockPosition = $this->getMock('Webfox\\Placements\\Domain\\Model\\Position');
		$mockType = $this->getMock('Webfox\Placements\Domain\Model\PositionType');
		$fixture->expects($this->once())->method('createDemandFromSettings')
			->with(NULL)->will($this->returnValue($mockDemand));
		$fixture->expects($this->once())->method('overwriteDemandObject')
			->with($mockDemand, $overwriteDemand)->will($this->returnValue($mockDemand));
		$fixture->_get('positionRepository')->expects($this->once())->method('findDemanded')->with($mockDemand, TRUE)
			->will($this->returnValue($mockQueryResult));
		$mockQueryResult->expects($this->once())->method('toArray')
			->will($this->returnValue(array($mockPosition)));
		$mockPosition->expects($this->once())->method('getType')->will($this->returnValue($mockType));
		$mockType->expects($this->once())->method('getUid')->will($this->returnValue(99));
		$mockType->expects($this->once())->method('getTitle')->will($this->returnValue('foo'));
		$mockPosition->expects($this->once())->method('getUid')->will($this->returnValue(1));
		$mockPosition->expects($this->once())->method('getTitle')->will($this->returnValue('bar'));
		$mockPosition->expects($this->once())->method('getSummary')->will($this->returnValue('baz'));
		$mockPosition->expects($this->once())->method('getCity')->will($this->returnValue('Leipzig'));
		$mockPosition->expects($this->once())->method('getZip')->will($this->returnValue('1234'));
		$mockPosition->expects($this->once())->method('getLatitude')->will($this->returnValue(1.2));
		$mockPosition->expects($this->once())->method('getLongitude')->will($this->returnValue(2.3));
		$expectedResult = json_encode(
			array(
				array(
					'uid' => 1,
					'title' => 'bar',
					'summary' => 'baz',
					'city' => 'Leipzig',
					'zip' => '1234',
					'latitude' => 1.2,
					'longitude' => 2.3,
					'type' => array(
						'uid' => 99,
						'title' => 'foo'
					)
				)
			)
		);
		$this->assertSame(
				$expectedResult,
				$fixture->ajaxListAction($overwriteDemand)
		);

	}

	/**
	 * @test
	 */
	public function ajaxShowActionReturnsCorrectResult() {
		$position = $this->getMock('\Webfox\Placements\Domain\Model\Position');
		$mockType = $this->getMock('\Webfox\Placements\Domain\Model\PositionType');

		$position->expects($this->once())->method('getType')->will($this->returnValue($mockType));
		$mockType->expects($this->once())->method('getUid')->will($this->returnValue(1));
		$mockType->expects($this->once())->method('getTitle')->will($this->returnValue('foo'));
		$this->fixture->_get('positionRepository')->expects($this->once())
			->method('findByUid')
			->with(99)
			->will($this->returnValue($position));

		$position->expects($this->once())->method('getUid')->will($this->returnValue(99));
		$position->expects($this->once())->method('getTitle')->will($this->returnValue('bar'));
		$position->expects($this->once())->method('getSummary')->will($this->returnValue('baz'));
		$position->expects($this->once())->method('getCity')->will($this->returnValue('Leipzig'));
		$position->expects($this->once())->method('getZip')->will($this->returnValue(123));
		$position->expects($this->once())->method('getLatitude')->will($this->returnValue(1.2));
		$position->expects($this->once())->method('getLongitude')->will($this->returnValue(3.4));
		$expectedResult = json_encode(
			array(
				array(
					'uid' => 99,
					'title' => 'bar',
					'summary' => 'baz',
					'city' => 'Leipzig',
					'zip' => 123,
					'latitude' => 1.2,
					'longitude' => 3.4,
					'type' => array(
						'uid' => 1,
						'title' => 'foo'
					)
				)
			)
		);

		$this->assertSame(
			$expectedResult,
			$this->fixture->ajaxShowAction(99)
		);
	}


	/**
	 * @test
	 * @covers ::showAction
	 */
	public function showActionAssignsVariables() {
		$mockPosition = $this->getMock('Webfox\Placements\Domain\Model\Position');
		$referrerArguments = array('foo' => 'bar');

		$this->fixture->_set('referrerArguments', $referrerArguments);
		$this->fixture->_get('view')->expects($this->once())
			->method('assignMultiple')
			->with(
					array(
						'position' => $mockPosition, 
						'referrerArguments' => $referrerArguments
					)
			);

		$this->fixture->showAction($mockPosition);
	}

	/**
	 * @test
	 * @covers ::newAction
	 */
	public function newActionRedirectsIfUserIsNotAllowedToCreatePosition() {
		$settings = array('listPid' => 99);

		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\PositionController',
			array('addFlashMessage', 'redirect', 'translate'), array(), '', FALSE);
		$accessControlService = $this->getMock(
				'Webfox\Placements\Service\AccessControlService',
				array('isAllowedToCreate'), array(), '', FALSE);
		$fixture->_set('settings', $settings);
		$fixture->_set('accessControlService', $accessControlService);
		$accessControlService->expects($this->once())
			->method('isAllowedToCreate')->with('position')
			->will($this->returnValue(FALSE));
		$fixture->expects($this->once())->method('translate')
			->with('tx_placements.error.position.createActionNotAllowed')
			->will($this->returnValue('foo'));
		$fixture->expects($this->once())->method('addFlashMessage')
			->with('foo');
		$fixture->expects($this->once())->method('redirect')
			->with('list', NULL, NULL, NULL, 99);

		$fixture->newAction();
	}

	/**
	 * @test
	 * @covers ::newAction
	 */
	public function newActionFindsAndAssignsObjects() {
		$settings = array(
				'listPid' => 99,
				'positionTypes' => '1,3,5',
				'workingHours' => '6,7,8',
				'categories' => '9',
				'sectors' => '10'
		);
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\PositionController',
			array('dummy'), array(), '', FALSE);
		$mockPositionTypeRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\PositionTypeRepository',
			array('findMultipleByUid'), array(), '', FALSE);
		$mockWorkingHoursRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\WorkingHoursRepository',
			array('findMultipleByUid'), array(), '', FALSE);
		$mockCategoryRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\CategoryRepository',
			array('findMultipleByUid'), array(), '', FALSE);
		$mockSectorRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\SectorRepository',
			array('findMultipleByUid'), array(), '', FALSE);
		$mockOrganizationRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\OrganizationRepository',
			array('findAll'), array(), '', FALSE);
		$mockView = $this->getMock(
			'\TYPO3\CMS\Fluid\View\TemplateView',
			array('assignMultiple'), array(), '', FALSE);
		$fixture->_set('positionTypeRepository',$mockPositionTypeRepository );
		$fixture->_set('workingHoursRepository',$mockWorkingHoursRepository );
		$fixture->_set('categoryRepository',$mockCategoryRepository );
		$fixture->_set('sectorRepository',$mockSectorRepository );
		$fixture->_set('organizationRepository',$mockOrganizationRepository );
		$fixture->_set('view', $mockView);
		$accessControlService = $this->getMock(
				'Webfox\Placements\Service\AccessControlService',
				array('isAllowedToCreate', 'getFrontendUser'), array(), '', FALSE);
		$fixture->_set('settings', $settings);
		$fixture->_set('accessControlService', $accessControlService);
		$accessControlService->expects($this->once())
			->method('isAllowedToCreate')->with('position')
			->will($this->returnValue(TRUE));

		$mockPositionTypeRepository->expects($this->once())->method('findMultipleByUid')
			->with('1,3,5')
			->will($this->returnValue('foo'));
		$mockWorkingHoursRepository->expects($this->once())->method('findMultipleByUid')
			->with('6,7,8')
			->will($this->returnValue('bar'));
		$mockCategoryRepository->expects($this->once())->method('findMultipleByUid')
			->with('9')
			->will($this->returnValue('baz'));
		$mockSectorRepository->expects($this->once())->method('findMultipleByUid')
			->with('10')
			->will($this->returnValue('boo'));
		$accessControlService->expects($this->once())->method('getFrontendUser');
		$mockOrganizationRepository->expects($this->once())->method('findAll')
			->will($this->returnValue('foobar'));
		$mockView->expects($this->once())->method('assignMultiple')
			->with(
					array(
						'newPosition' => null,
						'workingHours' => 'bar',
						'positionTypes' => 'foo',
						'categories' => 'baz',
						'sectors' => 'boo',
						'organizations' => 'foobar'
				));

		$fixture->newAction();
	}

	/**
	 * @test
	 * @covers ::newAction
	 */
	public function newActionFindsAndAssignsOrganizationsByClient() {
		$settings = array(
				'listPid' => 99,
				'positionTypes' => '1,3,5',
				'workingHours' => '6,7,8',
				'categories' => '9',
				'sectors' => '10'
		);
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\PositionController',
			array('dummy'), array(), '', FALSE);
		$mockPositionTypeRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\PositionTypeRepository',
			array('findMultipleByUid'), array(), '', FALSE);
		$mockWorkingHoursRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\WorkingHoursRepository',
			array('findMultipleByUid'), array(), '', FALSE);
		$mockCategoryRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\CategoryRepository',
			array('findMultipleByUid'), array(), '', FALSE);
		$mockSectorRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\SectorRepository',
			array('findMultipleByUid'), array(), '', FALSE);
		$mockOrganizationRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\OrganizationRepository',
			array('findByClient'), array(), '', FALSE);
		$mockView = $this->getMock(
			'\TYPO3\CMS\Fluid\View\TemplateView',
			array('assignMultiple'), array(), '', FALSE);
		$mockUser = $this->getMock('\Webfox\Placements\Domain\Model\User',
			array('getClient'), array(), '', FALSE);
		$fixture->_set('positionTypeRepository',$mockPositionTypeRepository );
		$fixture->_set('workingHoursRepository',$mockWorkingHoursRepository );
		$fixture->_set('categoryRepository',$mockCategoryRepository );
		$fixture->_set('sectorRepository',$mockSectorRepository );
		$fixture->_set('organizationRepository',$mockOrganizationRepository );
		$fixture->_set('view', $mockView);
		$accessControlService = $this->getMock(
				'Webfox\Placements\Service\AccessControlService',
				array('isAllowedToCreate', 'getFrontendUser'), array(), '', FALSE);
		$fixture->_set('settings', $settings);
		$fixture->_set('accessControlService', $accessControlService);
		$accessControlService->expects($this->once())
			->method('isAllowedToCreate')->with('position')
			->will($this->returnValue(TRUE));

		$mockPositionTypeRepository->expects($this->once())->method('findMultipleByUid');
		$mockWorkingHoursRepository->expects($this->once())->method('findMultipleByUid');
		$mockCategoryRepository->expects($this->once())->method('findMultipleByUid');
		$mockSectorRepository->expects($this->once())->method('findMultipleByUid');
		$accessControlService->expects($this->once())->method('getFrontendUser')
			->will($this->returnValue($mockUser));
		$mockUser->expects($this->exactly(2))->method('getClient')
			->will($this->returnValue(1));
		$mockOrganizationRepository->expects($this->once())->method('findByClient')
			->with(1)
			->will($this->returnValue('foobar'));
		$mockView->expects($this->once())->method('assignMultiple')
			->with(
					array(
						'newPosition' => null,
						'workingHours' => null,
						'positionTypes' => null,
						'categories' => null,
						'sectors' => null,
						'organizations' => 'foobar'
				));

		$fixture->newAction();
	}

	/**
	 * @test
	 * @covers ::createAction
	 */
	public function createActionUpdatesAndAddsPosition() {
		//setup
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\PositionController',
			array('translate', 'addFlashMessage', 'forward'), array(), '', FALSE);
		$settings = array('detailPid' => 2);
		$fixture->_set('settings', $settings);
		$mockAccessControlService = $this->getMock(
			'Webfox\Placements\Service\AccessControlService',
			array('getFrontendUser'), array(), '', FALSE);
		$fixture->_set('accessControlService', $mockAccessControlService);
		$mockRequest = $this->getMock(
				$this->buildAccessibleProxy('TYPO3\CMS\Extbase\MVC\Request'),
				array('hasArgument', 'getArgument'), array(), '', FALSE);
		$fixture->_set('request', $mockRequest);
		$mockGeoCoder = $this->getMock(
			'Webfox\Placements\Utiltiy\Geocoder',
			array('updateGeoLocation'), array(), '', FALSE);
		$fixture->_set('geoCoder', $mockGeoCoder);
		$mockRepository = $this->getMock(
			'Webfox\Placements\Domain\Repository\PositionRepository',
			array('add'), array(), '', FALSE);
		$fixture->_set('positionRepository', $mockRepository);
		$mockUser = $this->getMock(
			'Webfox\Placements\Domain\Model\User',
			array('getClient'), array(), '', FALSE);
		$mockClient = $this->getMock(
			'Webfox\Placements\Domain\Model\Client');
		$mockPosition = $this->getMock(
			'Webfox\Placements\Domain\Model\Position',
			array('setClient'), array(), '', FALSE);

		// expectations
		$mockAccessControlService->expects($this->once())->method('getFrontendUser')
			->will($this->returnValue($mockUser));
		$mockUser->expects($this->once())->method('getClient')
			->will($this->returnValue($mockClient));
		$mockRequest->expects($this->once())->method('getArgument')
			->with('newPosition');
		$mockGeoCoder->expects($this->once())->method('updateGeoLocation');
		$mockRepository->expects($this->once())->method('add')
			->with($mockPosition);
		$fixture->expects($this->once())->method('translate')
			->with('tx_placements.success.position.createAction')
			->will($this->returnValue('foo'));
		$fixture->expects($this->once())->method('addFlashMessage')
			->with('foo');
		$fixture->expects($this->once())->method('forward')
			->with('show', NULL, NULL, array('position' => $mockPosition), $settings['detailPid']);
		// call
		$fixture->createAction($mockPosition);
	}

	/**
	 * @test
	 * @covers ::createAction
	 */
	public function createActionAddsSingleCategory() {
		//setup
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\PositionController',
			array('translate', 'addFlashMessage', 'forward'), array(), '', FALSE);
		$settings = array('detailPid' => 2);
		$fixture->_set('settings', $settings);
		$mockAccessControlService = $this->getMock(
			'Webfox\Placements\Service\AccessControlService',
			array('getFrontendUser'), array(), '', FALSE);
		$mockClient = $this->getMock('Webfox\Placements\Domain\Model\Client');
		$fixture->_set('accessControlService', $mockAccessControlService);
		$mockRequest = $this->getMock(
				$this->buildAccessibleProxy('TYPO3\CMS\Extbase\MVC\Request'),
				array('hasArgument', 'getArgument'), array(), '', FALSE);
		$fixture->_set('request', $mockRequest);
		$mockGeoCoder = $this->getMock(
			'Webfox\Placements\Utiltiy\Geocoder',
			array('updateGeoLocation'), array(), '', FALSE);
		$fixture->_set('geoCoder', $mockGeoCoder);
		$mockPositionRepository = $this->getMock(
			'Webfox\Placements\Domain\Repository\PositionRepository',
			array('add'), array(), '', FALSE);
		$fixture->_set('positionRepository', $mockPositionRepository);
		$mockCategoryRepository = $this->getMock(
			'Webfox\Placements\Domain\Repository\CategoryRepository',
			array('findByUid'), array(), '', FALSE);
		$mockCategory = $this->getMock('Webfox\Placements\Domain\Model\Category');
		$fixture->_set('categoryRepository', $mockCategoryRepository);
		$mockUser = $this->getMock(
			'Webfox\Placements\Domain\Model\User',
			array('getClient'), array(), '', FALSE);
		$requestArgument = array(
			'categories' => '1'
		);
		$mockPosition = $this->getMock(
			'Webfox\Placements\Domain\Model\Position',
			array('setClient', 'addCategory'), array(), '', FALSE);

		// expectations
		$mockAccessControlService->expects($this->once())->method('getFrontendUser')
			->will($this->returnValue($mockUser));
		$mockUser->expects($this->once())->method('getClient')
			->will($this->returnValue($mockClient));
		$mockRequest->expects($this->once())->method('getArgument')
			->with('newPosition')
			->will($this->returnValue($requestArgument));
		$mockCategoryRepository->expects($this->once())->method('findByUid')
			->with('1')
			->will($this->returnValue(array($mockCategory)));
		$mockPosition->expects($this->once())->method('addCategory')
			->with($mockCategory);
		$mockGeoCoder->expects($this->once())->method('updateGeoLocation');
		$mockPositionRepository->expects($this->once())->method('add');
		$fixture->expects($this->once())->method('translate')
			->with('tx_placements.success.position.createAction')
			->will($this->returnValue('foo'));
		$fixture->expects($this->once())->method('addFlashMessage');
		$fixture->expects($this->once())->method('forward')
			->with('show', NULL, NULL, array('position' => $mockPosition), $settings['detailPid']);
		// call
		$fixture->createAction($mockPosition);
	}

	/**
	 * @test
	 * @covers ::createAction
	 */
	public function createActionAddsMultipleCategories() {
		//setup
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\PositionController',
			array('translate', 'addFlashMessage', 'forward'), array(), '', FALSE);
		$settings = array('detailPid' => 2);
		$fixture->_set('settings', $settings);
		$mockAccessControlService = $this->getMock(
			'Webfox\Placements\Service\AccessControlService',
			array('getFrontendUser'), array(), '', FALSE);
		$mockClient = $this->getMock('Webfox\Placements\Domain\Model\Client');
		$fixture->_set('accessControlService', $mockAccessControlService);
		$mockRequest = $this->getMock(
				$this->buildAccessibleProxy('TYPO3\CMS\Extbase\MVC\Request'),
				array('hasArgument', 'getArgument'), array(), '', FALSE);
		$fixture->_set('request', $mockRequest);
		$mockGeoCoder = $this->getMock(
			'Webfox\Placements\Utiltiy\Geocoder',
			array('updateGeoLocation'), array(), '', FALSE);
		$fixture->_set('geoCoder', $mockGeoCoder);
		$mockPositionRepository = $this->getMock(
			'Webfox\Placements\Domain\Repository\PositionRepository',
			array('add'), array(), '', FALSE);
		$fixture->_set('positionRepository', $mockPositionRepository);
		$mockCategoryRepository = $this->getMock(
			'Webfox\Placements\Domain\Repository\CategoryRepository',
			array('findMultipleByUid'), array(), '', FALSE);
		$mockCategory = $this->getMock('Webfox\Placements\Domain\Model\Category');
		$fixture->_set('categoryRepository', $mockCategoryRepository);
		$mockUser = $this->getMock(
			'Webfox\Placements\Domain\Model\User',
			array('getClient'), array(), '', FALSE);
		$requestArgument = array(
			'categories' => array(1,3,5)
		);
		$mockPosition = $this->getMock(
			'Webfox\Placements\Domain\Model\Position',
			array('setClient', 'addCategory'), array(), '', FALSE);

		// expectations
		$mockAccessControlService->expects($this->once())->method('getFrontendUser')
			->will($this->returnValue($mockUser));
		$mockUser->expects($this->once())->method('getClient')
			->will($this->returnValue($mockClient));
		$mockRequest->expects($this->once())->method('getArgument')
			->with('newPosition')
			->will($this->returnValue($requestArgument));
		$mockCategoryRepository->expects($this->once())->method('findMultipleByUid')
			->with('1,3,5')
			->will($this->returnValue(array($mockCategory, $mockCategory, $mockCategory)));
		$mockPosition->expects($this->exactly(3))->method('addCategory')
			->with($mockCategory);
		$mockGeoCoder->expects($this->once())->method('updateGeoLocation');
		$mockPositionRepository->expects($this->once())->method('add');
		$fixture->expects($this->once())->method('translate')
			->with('tx_placements.success.position.createAction')
			->will($this->returnValue('foo'));
		$fixture->expects($this->once())->method('addFlashMessage');
		$fixture->expects($this->once())->method('forward')
			->with('show', NULL, NULL, array('position' => $mockPosition), $settings['detailPid']);
		// call
		$fixture->createAction($mockPosition);
	}

	/**
	 * @test
	 * @covers ::editAction
	 */
	public function editActionRedirectsIfUserIsNotAllowedToEditPosition() {
		$settings = array('listPid' => 99);

		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\PositionController',
			array('addFlashMessage', 'redirect', 'translate'), array(), '', FALSE);
		$mockPosition = $this->getMock('Webfox\Placements\Domain\Model\Position');
		$accessControlService = $this->getMock(
				'Webfox\Placements\Service\AccessControlService',
				array('isAllowedToEdit'), array(), '', FALSE);
		$fixture->_set('settings', $settings);
		$fixture->_set('accessControlService', $accessControlService);
		$accessControlService->expects($this->once())
			->method('isAllowedToEdit')->with('position')
			->will($this->returnValue(FALSE));
		$fixture->expects($this->once())->method('translate')
			->with('tx_placements.error.position.editActionNotAllowed')
			->will($this->returnValue('foo'));
		$fixture->expects($this->once())->method('addFlashMessage')
			->with('foo');
		$fixture->expects($this->once())->method('redirect')
			->with('list', NULL, NULL, NULL, 99);

		$fixture->editAction($mockPosition);
	}

	/**
	 * @test
	 * @covers ::editAction
	 */
	public function editActionFindsAndAssignsObjects() {
		$settings = array(
				'listPid' => 99,
				'positionTypes' => '1,3,5',
				'workingHours' => '6,7,8',
				'categories' => '9',
				'sectors' => '10'
		);
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\PositionController',
			array('dummy'), array(), '', FALSE);
		$mockPosition = $this->getMock('Webfox\Placements\Domain\Model\Position');
		$mockPositionTypeRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\PositionTypeRepository',
			array('findMultipleByUid'), array(), '', FALSE);
		$mockWorkingHoursRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\WorkingHoursRepository',
			array('findMultipleByUid'), array(), '', FALSE);
		$mockCategoryRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\CategoryRepository',
			array('findMultipleByUid'), array(), '', FALSE);
		$mockSectorRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\SectorRepository',
			array('findMultipleByUid'), array(), '', FALSE);
		$mockOrganizationRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\OrganizationRepository',
			array('findAll'), array(), '', FALSE);
		$mockView = $this->getMock(
			'\TYPO3\CMS\Fluid\View\TemplateView',
			array('assignMultiple'), array(), '', FALSE);
		$fixture->_set('positionTypeRepository',$mockPositionTypeRepository );
		$fixture->_set('workingHoursRepository',$mockWorkingHoursRepository );
		$fixture->_set('categoryRepository',$mockCategoryRepository );
		$fixture->_set('sectorRepository',$mockSectorRepository );
		$fixture->_set('organizationRepository',$mockOrganizationRepository );
		$fixture->_set('view', $mockView);
		$accessControlService = $this->getMock(
				'Webfox\Placements\Service\AccessControlService',
				array('isAllowedToEdit', 'getFrontendUser'), array(), '', FALSE);
		$fixture->_set('settings', $settings);
		$fixture->_set('accessControlService', $accessControlService);
		$accessControlService->expects($this->once())
			->method('isAllowedToEdit')->with('position')
			->will($this->returnValue(TRUE));

		$mockPositionTypeRepository->expects($this->once())->method('findMultipleByUid')
			->with('1,3,5')
			->will($this->returnValue('foo'));
		$mockWorkingHoursRepository->expects($this->once())->method('findMultipleByUid')
			->with('6,7,8')
			->will($this->returnValue('bar'));
		$mockCategoryRepository->expects($this->once())->method('findMultipleByUid')
			->with('9')
			->will($this->returnValue('baz'));
		$mockSectorRepository->expects($this->once())->method('findMultipleByUid')
			->with('10')
			->will($this->returnValue('boo'));
		$accessControlService->expects($this->once())->method('getFrontendUser');
		$mockOrganizationRepository->expects($this->once())->method('findAll')
			->will($this->returnValue('foobar'));
		$mockView->expects($this->once())->method('assignMultiple')
			->with(
					array(
						'position' => $mockPosition,
						'workingHours' => 'bar',
						'positionTypes' => 'foo',
						'categories' => 'baz',
						'sectors' => 'boo',
						'organizations' => 'foobar'
				));

		$fixture->editAction($mockPosition);
	}

	/**
	 * @test
	 * @covers ::editAction
	 */
	public function editActionFindsAndAssignsOrganizationsByClient() {
		$settings = array(
				'listPid' => 99,
				'positionTypes' => '1,3,5',
				'workingHours' => '6,7,8',
				'categories' => '9',
				'sectors' => '10'
		);
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\PositionController',
			array('dummy'), array(), '', FALSE);
		$mockPosition = $this->getMock('Webfox\Placements\Domain\Model\Position');
		$mockPositionTypeRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\PositionTypeRepository',
			array('findMultipleByUid'), array(), '', FALSE);
		$mockWorkingHoursRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\WorkingHoursRepository',
			array('findMultipleByUid'), array(), '', FALSE);
		$mockCategoryRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\CategoryRepository',
			array('findMultipleByUid'), array(), '', FALSE);
		$mockSectorRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\SectorRepository',
			array('findMultipleByUid'), array(), '', FALSE);
		$mockOrganizationRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\OrganizationRepository',
			array('findByClient'), array(), '', FALSE);
		$mockView = $this->getMock(
			'\TYPO3\CMS\Fluid\View\TemplateView',
			array('assignMultiple'), array(), '', FALSE);
		$mockUser = $this->getMock('\Webfox\Placements\Domain\Model\User',
			array('getClient'), array(), '', FALSE);
		$fixture->_set('positionTypeRepository',$mockPositionTypeRepository );
		$fixture->_set('workingHoursRepository',$mockWorkingHoursRepository );
		$fixture->_set('categoryRepository',$mockCategoryRepository );
		$fixture->_set('sectorRepository',$mockSectorRepository );
		$fixture->_set('organizationRepository',$mockOrganizationRepository );
		$fixture->_set('view', $mockView);
		$accessControlService = $this->getMock(
				'Webfox\Placements\Service\AccessControlService',
				array('isAllowedToEdit', 'getFrontendUser'), array(), '', FALSE);
		$fixture->_set('settings', $settings);
		$fixture->_set('accessControlService', $accessControlService);
		$accessControlService->expects($this->once())
			->method('isAllowedToEdit')->with('position')
			->will($this->returnValue(TRUE));

		$mockPositionTypeRepository->expects($this->once())->method('findMultipleByUid');
		$mockWorkingHoursRepository->expects($this->once())->method('findMultipleByUid');
		$mockCategoryRepository->expects($this->once())->method('findMultipleByUid');
		$mockSectorRepository->expects($this->once())->method('findMultipleByUid');
		$accessControlService->expects($this->once())->method('getFrontendUser')
			->will($this->returnValue($mockUser));
		$mockUser->expects($this->exactly(2))->method('getClient')
			->will($this->returnValue(1));
		$mockOrganizationRepository->expects($this->once())->method('findByClient')
			->with(1)
			->will($this->returnValue('foobar'));
		$mockView->expects($this->once())->method('assignMultiple')
			->with(
					array(
						'position' => $mockPosition,
						'workingHours' => null,
						'positionTypes' => null,
						'categories' => null,
						'sectors' => null,
						'organizations' => 'foobar'
				));

		$fixture->editAction($mockPosition);
	}

	/**
	 * @test
	 * @covers ::updateAction
	 */
	public function updateActionUpdatesPosition() {
		//setup
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\PositionController',
			array('translate', 'addFlashMessage', 'forward'), array(), '', FALSE);
		$settings = array('detailPid' => 2);
		$fixture->_set('settings', $settings);
		$mockRequest = $this->getMock(
				$this->buildAccessibleProxy('TYPO3\CMS\Extbase\MVC\Request'),
				array('getArgument'), array(), '', FALSE);
		$fixture->_set('request', $mockRequest);
		$mockGeoCoder = $this->getMock(
			'Webfox\Placements\Utiltiy\Geocoder',
			array('updateGeoLocation'), array(), '', FALSE);
		$fixture->_set('geoCoder', $mockGeoCoder);
		$mockRepository = $this->getMock(
			'Webfox\Placements\Domain\Repository\PositionRepository',
			array('update'), array(), '', FALSE);
		$fixture->_set('positionRepository', $mockRepository);
		$mockPosition = $this->getMock(
			'Webfox\Placements\Domain\Model\Position',
			array(), array(), '', FALSE);

		// expectations
		$mockRequest->expects($this->once())->method('getArgument')
			->with('position');
		$mockGeoCoder->expects($this->once())->method('updateGeoLocation');
		$mockRepository->expects($this->once())->method('update')
			->with($mockPosition);
		$fixture->expects($this->once())->method('translate')
			->with('tx_placements.success.position.updateAction')
			->will($this->returnValue('foo'));
		$fixture->expects($this->once())->method('addFlashMessage')
			->with('foo');
		$fixture->expects($this->once())->method('forward')
			->with('show', NULL, NULL, array('position' => $mockPosition), $settings['detailPid']);
		// call
		$fixture->updateAction($mockPosition);
	}

	/**
	 * @test
	 * @covers ::updateAction
	 */
	public function updateActionUpdatesCategories() {
		//setup
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\PositionController',
			array('translate', 'addFlashMessage', 'forward'), array(), '', FALSE);
		$settings = array('detailPid' => 2);
		$fixture->_set('settings', $settings);
		$mockRequest = $this->getMock(
				$this->buildAccessibleProxy('TYPO3\CMS\Extbase\MVC\Request'),
				array('hasArgument', 'getArgument'), array(), '', FALSE);
		$mockObjectManager = $this->getMock(
			'\TYPO3\CMS\Extbase\Object\ObjectManager',
			array('get'), array(), '', FALSE);
		$fixture->_set('objectManager', $mockObjectManager);
		$fixture->_set('request', $mockRequest);
		$mockGeoCoder = $this->getMock(
			'Webfox\Placements\Utiltiy\Geocoder',
			array('updateGeoLocation'), array(), '', FALSE);
		$fixture->_set('geoCoder', $mockGeoCoder);
		$mockPositionRepository = $this->getMock(
			'Webfox\Placements\Domain\Repository\PositionRepository',
			array('update'), array(), '', FALSE);
		$fixture->_set('positionRepository', $mockPositionRepository);
		$mockCategoryRepository = $this->getMock(
			'Webfox\Placements\Domain\Repository\CategoryRepository',
			array('findMultipleByUid'), array(), '', FALSE);
		$mockCategory = $this->getMock('Webfox\Placements\Domain\Model\Category');
		$fixture->_set('categoryRepository', $mockCategoryRepository);
		$requestArgument = array(
			'categories' => array(1,3,5)
		);
		$mockPosition = $this->getMock(
			'Webfox\Placements\Domain\Model\Position',
			array('setCategories', 'addCategory'), array(), '', FALSE);
		$mockStorage = $this->getMock('TYPO3\CMS\Extbase\Persistence\ObjectStorage');

		// expectations
		$mockRequest->expects($this->once())->method('getArgument')
			->with('position')
			->will($this->returnValue($requestArgument));
		$mockCategoryRepository->expects($this->once())->method('findMultipleByUid')
			->with('1,3,5')
			->will($this->returnValue(array($mockCategory, $mockCategory, $mockCategory)));
		$mockObjectManager->expects($this->once())->method('get')
			->with('TYPO3\CMS\Extbase\Persistence\ObjectStorage')
			->will($this->returnValue($mockStorage));
		$mockPosition->expects($this->once())->method('setCategories')
			->with($mockStorage);
		$mockPosition->expects($this->exactly(3))->method('addCategory')
			->with($mockCategory);
		$mockGeoCoder->expects($this->once())->method('updateGeoLocation');
		$mockPositionRepository->expects($this->once())->method('update')
			->with($mockPosition);
		$fixture->expects($this->once())->method('translate')
			->with('tx_placements.success.position.updateAction')
			->will($this->returnValue('foo'));
		$fixture->expects($this->once())->method('addFlashMessage');
		$fixture->expects($this->once())->method('forward')
			->with('show', NULL, NULL, array('position' => $mockPosition), $settings['detailPid']);
		// call
		$fixture->updateAction($mockPosition);
	}

	/**
	 * @test
	 * @covers ::updateAction
	 */
	public function updateActionAddsSingleCategory() {
		//setup
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\PositionController',
			array('translate', 'addFlashMessage', 'forward'), array(), '', FALSE);
		$mockObjectManager = $this->getMock(
			'\TYPO3\CMS\Extbase\Object\ObjectManager',
			array('get'), array(), '', FALSE);
		$fixture->_set('objectManager', $mockObjectManager);
		$settings = array('detailPid' => 2);
		$fixture->_set('settings', $settings);
		$mockRequest = $this->getMock(
				$this->buildAccessibleProxy('TYPO3\CMS\Extbase\MVC\Request'),
				array('hasArgument', 'getArgument'), array(), '', FALSE);
		$fixture->_set('request', $mockRequest);
		$mockGeoCoder = $this->getMock(
			'Webfox\Placements\Utiltiy\Geocoder',
			array('updateGeoLocation'), array(), '', FALSE);
		$fixture->_set('geoCoder', $mockGeoCoder);
		$mockPositionRepository = $this->getMock(
			'Webfox\Placements\Domain\Repository\PositionRepository',
			array('update'), array(), '', FALSE);
		$fixture->_set('positionRepository', $mockPositionRepository);
		$mockCategoryRepository = $this->getMock(
			'Webfox\Placements\Domain\Repository\CategoryRepository',
			array('findByUid'), array(), '', FALSE);
		$mockCategory = $this->getMock('Webfox\Placements\Domain\Model\Category');
		$fixture->_set('categoryRepository', $mockCategoryRepository);
		$requestArgument = array(
			'categories' => '1'
		);
		$mockPosition = $this->getMock(
			'Webfox\Placements\Domain\Model\Position',
			array('setClient', 'addCategory'), array(), '', FALSE);
		$mockStorage = $this->getMock('TYPO3\CMS\Extbase\Persistence\ObjectStorage');

		// expectations
		$mockRequest->expects($this->once())->method('getArgument')
			->with('position')
			->will($this->returnValue($requestArgument));
		$mockCategoryRepository->expects($this->once())->method('findByUid')
			->with('1')
			->will($this->returnValue(array($mockCategory)));
		$mockObjectManager->expects($this->once())->method('get')
			->with('TYPO3\CMS\Extbase\Persistence\ObjectStorage')
			->will($this->returnValue($mockStorage));
		$mockPosition->expects($this->once())->method('addCategory')
			->with($mockCategory);
		$mockGeoCoder->expects($this->once())->method('updateGeoLocation');
		$mockPositionRepository->expects($this->once())->method('update');
		$fixture->expects($this->once())->method('translate')
			->with('tx_placements.success.position.updateAction')
			->will($this->returnValue('foo'));
		$fixture->expects($this->once())->method('addFlashMessage');
		$fixture->expects($this->once())->method('forward')
			->with('show', NULL, NULL, array('position' => $mockPosition), $settings['detailPid']);
		// call
		$fixture->updateAction($mockPosition);
	}


	/**
	 * @test
	 */
	public function countActionCallsFindDemandedAndAssignsVariables() {
		$fixture = $this->getAccessibleMock('Webfox\\Placements\\Controller\\PositionController',
			array('createDemandFromSettings', 'overwriteDemandObject', 'createSearchObject'), array(), '', FALSE);
		$mockRepository = $this->getMock('Webfox\\Placements\\Domain\\Repository\\PositionRepository', array('countDemanded'), array(), '', FALSE);
		$mockDemand = $this->getMock('Webfox\\Placements\\Domain\\Model\\Dto\\PositionDemand');
		$fixture->_set('positionRepository', $mockRepository);
		$fixture->_set('view', $this->getMock(
			'TYPO3\\CMS\Fluid\View\TemplateView', array(), array(), '', FALSE));
		$overwriteDemand = array(
			'search' => array(
				'subject' => 'bar'
			)
		);
		$settings = array(
			'position' => array(
				'search' => array(
					'fields' => 'foo'
				)
			)
		);
		$mockResult = $this->getMock(
				'\TYPO3\CMS\Extbase\Persistence\Generic\QueryResult',
				array(), array(), '', FALSE);
		$fixture->_set('settings', $settings);
		$mockSearchObject = $this->getMock('\Webfox\Placements\Domain\Model\Dto\Search');
		$fixture->expects($this->once())->method('createDemandFromSettings')
			->with($settings)->will($this->returnValue($mockDemand));
		$fixture->expects($this->once())->method('overwriteDemandObject')
			->with($mockDemand, $overwriteDemand)->will($this->returnValue($mockDemand));
		$fixture->expects($this->once())->method('createSearchObject')
			->with($overwriteDemand['search'], $settings['position']['search'])
			->will($this->returnValue($mockSearchObject));
		$mockDemand->expects($this->once())->method('setSearch')->with($mockSearchObject);
		$fixture->_get('positionRepository')->expects($this->once())
			->method('countDemanded')
			->with($mockDemand)
			->will($this->returnValue(1));
		$fixture->_get('view')->expects($this->once())
			->method('assignMultiple')
			->with(
				array(
					'count' => 1,
					'demand' => $mockDemand,
					'requestArguments' => null
				));

		$fixture->countAction($overwriteDemand);
	}

	/**
	 * @test
	 */
	public function createDemandFromSettingsCreatesDemand() {
		$mockDemand = $this->getMock('Webfox\\Placements\\Domain\\MOdel\\Dto\\PositionDemand');
		$settings = array(
			'orderBy' => 'foo',
			'orderDirection' => 'bar',
			'positionTypes' => '1,2,3',
			'workingHours' => 'baz',
			'categories' => '5,6',
			'sectors' => '7',
			'constraintsConjunction' => 'AND',
			'categoryConjunction' => 'OR',
			'clientsPositionsOnly' => TRUE,
			'limit' => '5'
		);
		$mockUser = $this->getMock('Webfox\\Placements\\Domain\\Model\\User');
		$mockClient = $this->getMock('Webfox\\Placements\\Domain\\Model\\Client');
		$this->fixture->_get('objectManager')->expects($this->once())->method('get')
			->with('Webfox\\Placements\\Domain\\Model\\Dto\\PositionDemand')
			->will($this->returnValue($mockDemand));
		$this->fixture->_get('accessControlService')->expects($this->once())->method('hasLoggedInClient')->will($this->returnValue(TRUE));
		$this->fixture->_get('accessControlService')->expects($this->once())->method('getFrontendUser')->will($this->returnValue($mockUser));
		$mockUser->expects($this->once())->method('getClient')->will($this->returnValue($mockClient));
		$mockClient->expects($this->once())->method('getUid')->will($this->returnValue(1));
		$mockDemand->expects($this->once())->method('setOrder')->with('foo|bar');
		$mockDemand->expects($this->once())->method('setPositionTypes')->with('1,2,3');
		$mockDemand->expects($this->once())->method('setWorkingHours')->with('baz');
		$mockDemand->expects($this->once())->method('setCategories')->with('5,6');
		$mockDemand->expects($this->once())->method('setSectors')->with('7');
		$mockDemand->expects($this->once())->method('setConstraintsConjunction')->with('AND');
		$mockDemand->expects($this->once())->method('setCategoryConjunction')->with('OR');
		$mockDemand->expects($this->once())->method('setClients')->with(1);
		$mockDemand->expects($this->once())->method('setClientsPositionsOnly')->with(TRUE);
		$mockDemand->expects($this->once())->method('setLimit')->with('5');

		$this->fixture->createDemandFromSettings($settings);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsEmptyStringForClients() {
		$mockDemand = $this->getMock('Webfox\\Placements\\Domain\\Model\\Dto\PositionDemand');
		$overwriteDemand = array(
			'clientsPositionsOnly' => TRUE
		);
		$this->fixture->_get('accessControlService')->expects($this->once())
			->method('hasLoggedInClient')
			->will($this->returnValue(FALSE));
		$mockDemand->expects($this->once())->method('setClients')->with('');

		$this->fixture->_call('overwriteDemandObject', $mockDemand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsClients() {
		$mockDemand = $this->getMock('Webfox\\Placements\\Domain\\Model\\Dto\PositionDemand');
		$overwriteDemand = array(
			'clientsPositionsOnly' => TRUE
		);
		$mockUser = $this->getMock('Webfox\\Placementst\\Domain\\Model\User',
				array('getClient'));
		$mockClient = $this->getMock('Webfox\\Placementst\\Domain\\Model\Client',
				array('getUid'));
		$this->fixture->_get('accessControlService')->expects($this->once())
			->method('hasLoggedInClient')
			->will($this->returnValue(TRUE));
		$this->fixture->_get('accessControlService')->expects($this->once())
			->method('getFrontendUser')->will($this->returnValue($mockUser));
		$mockUser->expects($this->once())->method('getClient')->will($this->returnValue($mockClient));
		$mockClient->expects($this->once())->method('getUid')->will($this->returnValue(1));
		$mockDemand->expects($this->once())->method('setClients')->with('1');

		$this->fixture->_call('overwriteDemandObject', $mockDemand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectSetsOrderByWithOrderDirectionFromSettings() {
		$mockDemand = $this->getMock('Webfox\\Placements\\Domain\\Model\\Dto\PositionDemand');
		$overwriteDemand = array(
			'orderBy' => 'foo'
		);
		$settings = array(
			'orderDirection' => 'asc'
		);
		$this->fixture->_set('settings', $settings);
		$mockDemand->expects($this->once())->method('setOrder')->with('foo|asc');

		$this->fixture->_call('overwriteDemandObject', $mockDemand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectOverwritesOrderDirectionFromSettings() {
		$mockDemand = $this->getMock('Webfox\\Placements\\Domain\\Model\\Dto\PositionDemand');
		$overwriteDemand = array(
			'orderBy' => 'foo',
			'orderDirection' => 'desc'
		);
		$settings = array(
			'orderDirection' => 'asc'
		);
		$this->fixture->_set('settings', $settings);
		$mockDemand->expects($this->once())->method('setOrder')->with('foo|desc');

		$this->fixture->_call('overwriteDemandObject', $mockDemand, $overwriteDemand);
	}

	/**
	 * @test
	 * @covers ::overwriteDemandObject
	 */
	public function overwriteDemandObjectCreatesSearchObjectAndSetsSearch() {
		$fixture = $this->getAccessibleMock('Webfox\\Placements\\Controller\PositionController',
			array('createSearchObject'));
		$mockDemand = $this->getMock('Webfox\\Placements\\Domain\\Model\\Dto\PositionDemand',
				array('setRadius', 'setGeoLocation', 'setSearch', 'getSearch'));
		$mockGeoCoder = $this->getMock('Webfox\\Placements\\Utility\\GeoCoder', array('getLocation'));
		$fixture->_set('geoCoder', $mockGeoCoder);
		$overwriteDemand = array(
			'search' => array(
				'subject' => 'foo',
				'location' => 'bar',
				'radius' => 1000,
				'bounds' => 'baz'
			)
		);
		$geoLocation = array('foo' => 'bar');
		$settings = array(
			'position' => array('search' => 'bar')
		);
		$mockSearch = $this->getMock('Webfox\\Placements\\Domain\\Model\\Dto\\Search',
				array('setFields', 'setSubject', 'getRadius', 'getLocation', 'setRadius', 'setLocation'));
		$fixture->_set('settings', $settings);
		$fixture->expects($this->once())->method('createSearchObject')
			->with($overwriteDemand['search'], $settings['position']['search'])
			->will($this->returnValue($mockSearch));
		$mockDemand->expects($this->once())->method('setSearch')->with($mockSearch);
		$mockDemand->expects($this->any())->method('getSearch')
			->will($this->returnValue($mockSearch));
		$mockSearch->expects($this->any())->method('getRadius')
			->will($this->returnValue($overwriteDemand['search']['radius']));
		$mockSearch->expects($this->any())->method('getLocation')
			->will($this->returnValue('baz'));
		$mockGeoCoder->expects($this->once())->method('getLocation')
			->will($this->returnValue($geoLocation));
		$mockDemand->expects($this->once())->method('setRadius')
			->with($overwriteDemand['search']['radius']);
		$mockDemand->expects($this->once())->method('setGeoLocation')
			->with($geoLocation);
		$fixture->_call('overwriteDemandObject', $mockDemand, $overwriteDemand);
	}
}
?>

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
 * Test case for class Tx_Placements_Controller_PositionController.
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
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function processRequestHandlesTargetNotFoundException() {
		$this->markTestSkipped();
		$mockOrganization = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');
		$mockRequest = $this->getMock(
				$this->buildAccessibleProxy('TYPO3\CMS\Extbase\MVC\Request'), array('dummy'), array(), '', FALSE);
		$mockRequest->_set('pluginName', 'Placements');
		$mockRequest->_set('controllerName', 'PositionController');
		$mockRequest->_set('arguments', array(
					'position' => $mockPosition,));
		$this->fixture->_set('request', $mockRequest);
		
		$mockResponse = $this->getMock(
			'\TYPO3\CMS\Extbase\Mvc\ResponseInterface');
		$mockUriBuilder = $this->getMock(
			'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder');

		$this->fixture->_get('objectManager')->expects($this->once())
			->method('get')
			->with('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder')
			->will($this->returnValue($mockUriBuilder));
		$this->fixture->expects($this->once())
			->method('mapRequestArgumentsToControllerArguments');

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
		$overwriteDemand = array(
			'foo' => 'bar',
		);
		$mockResult = $this->getMock(
				'\TYPO3\CMS\Extbase\Persistence\Generic\QueryResult',
				array(), array(), '', FALSE);
		$this->fixture->_set('settings', $settings);
		$mockDemand = $this->getMock('\Webfox\Placements\Domain\Model\Dto\PositionDemand');
		$mockMessageQueue = $this->getMock(
			'\TYPO3\CMS\Core\Messaging\FlashMessageQueue', array(), array(), '', FALSE);
	/*	$this->fixture->expects($this->once())
			->method('overwriteDemandObject')
			->with($mockDemand, $overwriteDemand)
			->will($this->returnValue($mockDemand));*/
		$this->fixture->_get('objectManager')->expects($this->once())
			->method('get')->will($this->returnValue($mockDemand));
		$this->fixture->_get('positionRepository')->expects($this->once())
			->method('findDemanded')
			->with($mockDemand)
			->will($this->returnValue($mockResult));
		$this->fixture->_get('controllerContext')->expects($this->once())
			->method('getFlashMessageQueue')
			->will($this->returnValue($mockMessageQueue));
		$this->fixture->_get('view')->expects($this->once())
			->method('assignMultiple')
			->with(
				array(
					'positions' => $mockResult,
					'overwriteDemand' => $overwriteDemand,
					'requestArguments' => null
				));

		$this->fixture->listAction($overwriteDemand);
	}
}
?>

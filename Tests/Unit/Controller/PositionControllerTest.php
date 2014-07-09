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
		$objectManager = $this->getMock('\\TYPO3\\CMS\\Extbase\\Object\\ObjectManager', array(), array(), '', FALSE);
		$this->fixture = $this->getAccessibleMock(
			'\Webfox\Placements\Controller\PositionController',
			array('dummy'), array(), '', FALSE);
		$this->fixture->_set('objectManager', $objectManager);
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function dummyMethod() {
		$this->markTestIncomplete();
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
}
?>

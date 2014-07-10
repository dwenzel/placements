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
 * Test case for class Webfox\Placements\Controller\AbstractController.
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
class AbstractControllerTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\Placements\Controller\AbstractController
	 */
	protected $fixture;
	/**
	* @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
	*/
	protected $tsfe = NULL;

	public function setUp() {
		$this->tsfe = $this->getAccessibleMock('tslib_fe', array('pageNotFoundAndExit'), array(), '', FALSE);
		$GLOBALS['TSFE'] = $this->tsfe;
		//$objectManager = new \TYPO3\CMS\Extbase\Object\ObjectManager();
		$objectManager = $this->getMock('\\TYPO3\\CMS\\Extbase\\Object\\ObjectManager', array(), array(), '', FALSE);
		$this->fixture = $this->getAccessibleMock(
			'\Webfox\Placements\Controller\AbstractController', array('dummy'), array(), '', FALSE);
	/*	$this->abstractRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\AbstractDemandedRepository', array(), array(), '', FALSE
		);*/
		$this->fixture->_set('objectManager', $objectManager);
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function dummy() {
			$this->markTestIncomplete();
	}

	/**
	 * @test
	 */
	public function emptyHandleEntityNotFoundErrorConfigurationReturnsNull() {
		$result = $this->fixture->_call('handleEntityNotFoundError', '');
		$this->assertNull($result);
	}

	/**
	 * @test
	 */
	public function invalidHandleEntityNotFoundErrorConfigurationReturnsNull() {
		$result = $this->fixture->_call('handleEntityNotFoundError', 'baz');
		$this->assertNull($result);
	}

	/**
	 * @test
	 */
	public function handleEntityNotFoundErrorConfigurationRedirectsToListView() {
		$mockController = $this->getAccessibleMock(
			'Webfox\Placements\Controller\AbstractController', array('redirect'));
		$mockController->expects($this->once())
			->method('redirect')
			->with('list');
		$mockController->_call('handleEntityNotFoundError', 'redirectToListView');
	}

	/**
	 * @test
	 */
	public function handleEntityNotFoundErrorConfigurationCallsPageNotFoundHandler() {
		$this->tsfe->expects($this->once())
			->method('pageNotFoundAndExit')
			->with($this->fixture->_get('entityNotFoundMessage'));
		$this->fixture->_call('handleEntityNotFoundError', 'pageNotFoundHandler');
	}

	/**
	 * @test
	 * @expectedException \InvalidArgumentException	 
	 */
	public function handleEntityNotFoundErrorConfigurationWithTooLessOptionsForRedirectToPageThrowsError() {
		$this->fixture->_call('handleEntityNotFoundError', 'redirectToPage');
	}

	/**
	 * @test
	 * @expectedException \InvalidArgumentException	 
	 */
	public function handleEntityNotFoundErrorConfigurationWithTooManyOptionsForRedirectToPageThrowsError() {
		$this->fixture->_call('handleEntityNotFoundError', 'redirectToPage, arg1, arg2, arg3');
	}

	/**
	 * @test
	 */
	public function handleEntityNotFoundErrorConfigurationRedirectsToCorrectPage() {
		$mockController = $this->getAccessibleMock(
			'Webfox\Placements\Controller\AbstractController', array('redirectToUri'));
		$mockUriBuilder = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder');
		$mockController->_set('uriBuilder', $mockUriBuilder);
		$mockUriBuilder->expects($this->once())
			->method('setTargetPageUid')
			->with('55');
		$mockUriBuilder->expects($this->once())
			->method('build');
		$mockController->_call('handleEntityNotFoundError', 'redirectToPage, 55');
	}

	/**
	 * @test
	 */
	public function handleEntityNotFoundErrorConfigurationRedirectsToCorrectPageWithStatus() {
		$mockController = $this->getAccessibleMock(
			'Webfox\Placements\Controller\AbstractController', array('redirectToUri'));
		$mockUriBuilder = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder');
		$mockController->_set('uriBuilder', $mockUriBuilder);
		$mockUriBuilder->expects($this->once())
			->method('setTargetPageUid')
			->with('1');
		$mockUriBuilder->expects($this->once())
			->method('build');
		$mockController->expects($this->once())
			->method('redirectToUri')
			->with(null, 0, '301');
		$mockController->_call('handleEntityNotFoundError', 'redirectToPage, 1, 301');
	}

	/**
	 * @test
	 */
	public function initializeActionSetsReferrerArgumentsInitiallyToEmptyArray() {
		$arguments = array(
			'action' => 'foo',
			'controller' => 'bar'
		);
		$mockRequest = $this->getMock(
				'TYPO3\CMS\Extbase\Mvs\Web\Request',
				array(
					'getArguments',
					'getPluginName',
					'getControllerName',
					'getControllerExtensionName',
					'hasArgument'));
		$this->fixture->_set('request', $mockRequest);
		$mockRequest->expects($this->once())
			->method('getArguments')
			->will($this->returnValue($arguments));
		$mockRequest->expects($this->once())
			->method('hasArgument')
			->with('referrerArguments')
			->will($this->returnValue(FALSE));
		$this->fixture->_call('initializeAction');
		$this->assertSame(
			array(),
			$this->fixture->_get('referrerArguments')
		);
	}

	/**
	 * @test
	 */
	public function initializeActionSetsReferrerArguments() {
		$originalRequestArguments = array(
			'action' => 'foo',
			'controller' => 'bar',
			'arguments' => array(
				'referrerArguments' => array(
					'foo' => 'bar'
				)
			)
		);
		$result = array(
			'foo' => 'bar'
		);
		$mockRequest = $this->getMock(
				'TYPO3\CMS\Extbase\Mvs\Web\Request',
				array(
					'getArguments',
					'getPluginName',
					'getControllerName',
					'getControllerExtensionName',
					'hasArgument',
					'getArgument'));
		$this->fixture->_set('request', $mockRequest);
		$mockRequest->expects($this->once())
			->method('getArguments')
			->will($this->returnValue($originalRequestArguments));
		$mockRequest->expects($this->once())
			->method('hasArgument')
			->with('referrerArguments')
			->will($this->returnValue(TRUE));
		$mockRequest->expects($this->exactly(2))
			->method('getArgument')
			->with('referrerArguments')
			->will($this->returnValue($originalRequestArguments['arguments']['referrerArguments']));

		$this->fixture->_call('initializeAction');
		$this->assertSame(
			$result,
			$this->fixture->_get('referrerArguments')
		);
	}

	/**
	 * @test
	 */
	public function uploadFileHandlesUpload() {
		$fileName = 'foo.bar';
		$fileTmpName = 'xyz';
		$mockFileUtility = $this->getMock(
			'TYPO3\CMS\Core\Utility\File\BasicFileUtility');
		$this->fixture->_get('objectManager')->expects($this->once())
			->method('get')
			->with('TYPO3\CMS\Core\Utility\File\BasicFileUtility')
			->will($this->returnValue($mockFileUtility));
		$mockFileUtility->expects($this->once())
			->method('getUniqueName')
			->with($fileName, \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('uploads/tx_placements'))
			->will($this->returnValue('foo1.bar'));
		$mockFileUtility->expects($this->once())
			->method('getTotalFileInfo')
			->with('foo1.bar')
			->will($this->returnValue( array(
						'file' => 'realFileName')));
		$this->assertSame(
			'realFileName',
			$this->fixture->_call('uploadFile', $fileName, $fileTmpName)
		);
	}
}
?>

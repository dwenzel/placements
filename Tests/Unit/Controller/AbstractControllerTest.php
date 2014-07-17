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
		$objectManager = $this->getMock('\\TYPO3\\CMS\\Extbase\\Object\\ObjectManager', array(), array(), '', FALSE);
		$this->fixture = $this->getAccessibleMock(
			'\Webfox\Placements\Controller\AbstractController', array('dummy'), array(), '', FALSE);
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
	public function classHasAttributeGeoCoder() {
			$this->assertClassHasAttribute('geoCoder', '\Webfox\Placements\Controller\AbstractController');
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
		$this->tsfe = $this->getAccessibleMock('tslib_fe', array('pageNotFoundAndExit'), array(), '', FALSE);
		$GLOBALS['TSFE'] = $this->tsfe;
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

	/**
	 * @test
	 */
	public function updateFilePropertySetsPropertyForProperFileValue() {
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\AbstractController', array('uploadFile'));
		$mockObject = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');
		$fileProperty = array(
			'name' => 'foo',
			'tmp_name' => 'bar',
			'error' => null
		);
		$mockObject->expects($this->any())
			->method('getImage')
			->will($this->returnValue($fileProperty));
		$fixture->expects($this->once())
			->method('uploadFile')
			->with($fileProperty['name'], $fileProperty['tmp_name'])
			->will($this->returnValue('realFileName'));
		$mockObject->expects($this->once())
			->method('setImage')
			->with('realFileName');
		$fixture->_call('updateFileProperty', $mockObject, 'image');
	}

	/**
	 * @test
	 */
	public function updateFilePropertyAddsErrorMessageForMaximumServerFileSize() {
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\AbstractController',
			array('uploadFile', 'addFlashMessage'));
		$mockObject = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');
		$fileProperty = array(
			'name' => 'foo',
			'tmp_name' => 'bar',
			'error' => 1
		);

		$mockObject->expects($this->any())
			->method('getImage')
			->will($this->returnValue($fileProperty));
		$fixture->expects($this->never())
			->method('uploadFile');
		$mockObject->expects($this->once())
			->method('_memorizeCleanState')
			->with('image');
		$fixture->expects($this->once())
			->method('addFlashMessage')
			->with('The uploaded file exceeds the servers maximum file size directive.');
		$fixture->_call('updateFileProperty', $mockObject, 'image');
	}

	/**
	 * @test
	 */
	public function updateFilePropertyAddsErrorMessageForMaximumServerFormFileSize() {
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\AbstractController',
			array('uploadFile', 'addFlashMessage'));
		$mockObject = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');
		$fileProperty = array(
			'name' => 'foo',
			'tmp_name' => 'bar',
			'error' => 2
		);

		$mockObject->expects($this->any())
			->method('getImage')
			->will($this->returnValue($fileProperty));
		$fixture->expects($this->never())
			->method('uploadFile');
		$mockObject->expects($this->once())
			->method('_memorizeCleanState')
			->with('image');
		$fixture->expects($this->once())
			->method('addFlashMessage')
			->with('The uploaded file exceeds the maximum file size directive that was specified in the HTML form.');
		$fixture->_call('updateFileProperty', $mockObject, 'image');
	}

	/**
	 * @test
	 */
	public function updateFilePropertyAddsErrorMessageForPartialFileUpload() {
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\AbstractController',
			array('uploadFile', 'addFlashMessage'));
		$mockObject = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');
		$fileProperty = array(
			'name' => 'foo',
			'tmp_name' => 'bar',
			'error' => 3
		);

		$mockObject->expects($this->any())
			->method('getImage')
			->will($this->returnValue($fileProperty));
		$fixture->expects($this->never())
			->method('uploadFile');
		$mockObject->expects($this->once())
			->method('_memorizeCleanState')
			->with('image');
		$fixture->expects($this->once())
			->method('addFlashMessage')
			->with('The file was only partially uploaded.');
		$fixture->_call('updateFileProperty', $mockObject, 'image');
	}

	/**
	 * @test
	 */
	public function updateFilePropertyAddsErrorMessageForNoFileUploaded() {
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\AbstractController',
			array('uploadFile', 'addFlashMessage'));
		$mockObject = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');
		$fileProperty = array(
			'error' => 4
		);

		$mockObject->expects($this->any())
			->method('getImage')
			->will($this->returnValue($fileProperty));
		$fixture->expects($this->once())
			->method('addFlashMessage')
			->with('No file was uploaded.');
		$fixture->_call('updateFileProperty', $mockObject, 'image');
	}

	/**
	 * @test
	 */
	public function updateFilePropertyAddsErrorMessageForMissingTempFolder() {
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\AbstractController',
			array('uploadFile', 'addFlashMessage'));
		$mockObject = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');
		$fileProperty = array(
			'error' => 6
		);

		$mockObject->expects($this->any())
			->method('getImage')
			->will($this->returnValue($fileProperty));
		$fixture->expects($this->once())
			->method('addFlashMessage')
			->with('Missing a temporary folder.');
		$fixture->_call('updateFileProperty', $mockObject, 'image');
	}

	/**
	 * @test
	 */
	public function updateFilePropertyAddsErrorMessageForFailedWriteToDisk() {
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\AbstractController',
			array('uploadFile', 'addFlashMessage'));
		$mockObject = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');
		$fileProperty = array(
			'error' => 7
		);

		$mockObject->expects($this->any())
			->method('getImage')
			->will($this->returnValue($fileProperty));
		$fixture->expects($this->once())
			->method('addFlashMessage')
			->with('Failed to write file to disk.');
		$fixture->_call('updateFileProperty', $mockObject, 'image');
	}

	/**
	 * @test
	 */
	public function updateFilePropertyAddsErrorMessageForFileUploadStoppedByExtension() {
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\AbstractController',
			array('uploadFile', 'addFlashMessage'));
		$mockObject = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');
		$fileProperty = array(
			'error' => 8
		);

		$mockObject->expects($this->any())
			->method('getImage')
			->will($this->returnValue($fileProperty));
		$fixture->expects($this->once())
			->method('addFlashMessage')
			->with('File upload stopped by extension.');
		$fixture->_call('updateFileProperty', $mockObject, 'image');
	}

	/**
	 * @test
	 */
	public function updateFilePropertyAddsErrorMessageForUnknownError() {
		$fixture = $this->getAccessibleMock(
			'Webfox\Placements\Controller\AbstractController',
			array('uploadFile', 'addFlashMessage'));
		$mockObject = $this->getMock(
			'Webfox\Placements\Domain\Model\Organization');
		$fileProperty = array(
			'error' => 99
		);

		$mockObject->expects($this->any())
			->method('getImage')
			->will($this->returnValue($fileProperty));
		$fixture->expects($this->once())
			->method('addFlashMessage')
			->with('Unknown file upload error.');
		$fixture->_call('updateFileProperty', $mockObject, 'image');
	}

	/**
	 * @test
	 */
	public function createSearchObjectReturnsInitiallyEmptySearchObject() {
		$mockSearchObject = $this->getMock('Webfox\Placements\Domain\Model\Dto\Search');
		$emptySearch = array();
		$emptySettings = array();

		$this->fixture->_get('objectManager')->expects($this->once())
			->method('get')
			->with('Webfox\Placements\Domain\Model\Dto\Search')
			->will($this->returnValue($mockSearchObject));

		$this->assertSame(
			$mockSearchObject,
			$this->fixture->createSearchObject($emptySearch, $emptySettings)
		);
	}

	/**
	 * @test
	 */
	public function createSearchObjectSetsSearchFields() {
		$mockSearchObject = $this->getMock('Webfox\Placements\Domain\Model\Dto\Search',
			array());
		$searchRequest = array(
			'subject' => 'foo',
			'location' => 'bar',
			'radius' => 50000,
		);
		$settings = array(
			'fields' => 'foo,bar'
		);

		$expectedSearchObject = $this->getAccessibleMock('Webfox\Placements\Domain\Model\Dto\Search');
		$expectedSearchObject->_set('fields', $settings['fields']);
		$expectedSearchObject->_set('subject', $searchRequest['subject']);

		$this->fixture->_get('objectManager')->expects($this->once())
			->method('get')
			->with('Webfox\Placements\Domain\Model\Dto\Search')
			->will($this->returnValue($mockSearchObject));
		$mockSearchObject->expects($this->once())
			->method('setFields')
			->with('foo,bar');
		$mockSearchObject->expects($this->once())
			->method('setSubject')
			->with('foo');
		$mockSearchObject->expects($this->once())
			->method('setLocation')
			->with('bar');
		$mockSearchObject->expects($this->once())
			->method('setRadius')
			->with(50000);
		$result = $this->fixture->createSearchObject($searchRequest, $settings);
	}
}
?>

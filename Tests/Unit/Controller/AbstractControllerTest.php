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
 * @coversDefaultClass \Webfox\Placements\Controller\AbstractController
 */
class AbstractControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
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
	public function classHasAttributePositionRepository() {
			$this->assertClassHasAttribute('positionRepository', '\Webfox\Placements\Controller\AbstractController');
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
	 * @covers ::getMappingConfigurationForProperty
	 */
	public function getMappingConfigurationForPropertyReturnsInitiallyNull() {
		$mockArguments = $this->getMock('TYPO3\CMS\Extbase\Mvc\Controller\Arguments',
				array('hasArgument'));
		$this->fixture->_set('arguments', $mockArguments);

		$mockArguments->expects($this->once())->method('hasArgument');
		$this->assertNull(
				$this->fixture->_call('getMappingConfigurationForProperty', 'foo', 'bar.baz')
		);
	}

	/**
	 * @test
	 * @covers ::getMappingConfigurationForProperty
	 */
	public function getMappingConfigurationForPropertyReturnsNullIfArgumentNotFound() {
		$mockArguments = $this->getMock('TYPO3\CMS\Extbase\Mvc\Controller\Arguments',
				array('hasArgument'));
		$this->fixture->_set('arguments', $mockArguments);

		$mockArguments->expects($this->once())->method('hasArgument')
			->with('foo')
			->will($this->returnValue(FALSE));

		$this->assertNull(
				$this->fixture->_call('getMappingConfigurationForProperty', 'foo', 'bar.baz')
		);
	}

	/**
	 * @test
	 * @covers ::getMappingConfigurationForProperty
	 */
	public function getMappingConfigurationForPropertyReturnsConfiguration() {
		$mockArguments = $this->getMock('TYPO3\CMS\Extbase\Mvc\Controller\Arguments',
				array('hasArgument', 'getArgument'));
		$this->fixture->_set('arguments', $mockArguments);
		$mockArgument = $this->getMock('\TYPO3\CMS\Extbase\Mvc\Controller\Argument',
				array('getPropertyMappingConfiguration'), array(), '', FALSE);
		$mockPropertyMappingConfiguration = $this->getMock(
				'\TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfiguration',
				array('forProperty'), array(), '', FALSE);
		$mockConfiguration = $this->getMock('\TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration');

		$mockArguments->expects($this->once())->method('hasArgument')
			->with('foo')
			->will($this->returnValue(TRUE));
		$mockArguments->expects($this->once())->method('getArgument')
			->with('foo')
			->will($this->returnValue($mockArgument));
		$mockArgument->expects($this->once())->method('getPropertyMappingConfiguration')
			->will($this->returnValue($mockPropertyMappingConfiguration));
		$mockPropertyMappingConfiguration->expects($this->once())->method('forProperty')
			->with('bar.baz')
			->will($this->returnValue($mockConfiguration));

		$this->assertSame(
				$mockConfiguration,
				$this->fixture->_call('getMappingConfigurationForProperty', 'foo', 'bar.baz')
		);
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

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
			'Webfox\\Placements\\Controller\\AbstractController', array('dummy'), array(), '', FALSE);
	/*	$this->abstractRepository = $this->getMock(
			'\Webfox\Placements\Domain\Repository\AbstractDemandedRepository', array(), array(), '', FALSE
		);*/
		$this->fixture->injectObjectManager($objectManager);
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

}
?>

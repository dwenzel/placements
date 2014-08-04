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
 * Test case for class \Webfox\Placements\Service\AccessControlService.
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
 * @coversDefaultClass \Webfox\Placements\Service\AccessControlService
 */
class AccessControlServiceTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \Webfox\Placements\Service\AccessControlService
	 */
	protected $fixture;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
	 */
	protected $tsfe = NULL;

	public function setUp() {
		$this->fixture = $this->getAccessibleMock(
				'\Webfox\Placements\Service\AccessControlService',
				array('dummy'), array(), '', FALSE);
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
	 * @covers ::getTyposcriptSettings
	 */
	public function getTyposcriptSettingsLoadsAndReturnsTyposcriptSettings () {
		$mockConfigurationManager = $this->getMock(
			'\TYPO3\CMS\Extbase\Configuration\ConfigurationManager',
			array('getConfiguration'), array(), '', FALSE);
		$this->fixture->_set('configurationManager', $mockConfigurationManager);
		$settings = array('foo' => 'bar');

		$mockConfigurationManager->expects($this->once())->method('getConfiguration')
			->with(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS)
			->will($this->returnValue($settings));

		$this->assertSame(
				$settings,
				$this->fixture->getTyposcriptSettings()
		);
	}

	/**
	 * @test
	 * @covers ::getTyposcriptSettings
	 */
	public function getTyposcriptSettingsReturnsTyposcriptSettingsIfSet () {
		$settings = array('foo' => 'bar');
		$this->fixture->_set('typoscriptSettings', $settings);

		$this->assertSame(
				$settings,
				$this->fixture->getTyposcriptSettings()
		);
	}

	/**
	 * @test
	 * @covers ::getFrontendUser
	 */
	public function getFrontendUserReturnsFrontendUserIfIsSet() {
		$mockUser = $this->getMock('\Webfox\Placements\Domain\Model\User');
		$this->fixture->_set('frontendUser', $mockUser);

		$this->assertSame(
			$mockUser,
			$this->fixture->getFrontendUser()
		);
	}

	/**
	 * @test
	 * @covers ::getFrontendUser
	 */
	public function getFrontendUserFindsAndReturnsFrontendUser() {
		$fixture = $this->getAccessibleMock(
				'\Webfox\Placements\Service\AccessControlService',
				array('hasLoggedInFrontendUser'), array(), '', FALSE);

		$mockUserRepository = $this->getMock(
				'\Webfox\Placements\Domain\Repository\UserRepository',
				array('findOneByUid'), array(), '', FALSE);
		$mockUser = $this->getMock('\Webfox\Placements\Domain\Model\User');
		$fixture->_set('userRepository', $mockUserRepository);
		$mockAttributeUser = array('uid' => '5');
		$mockFe_user = $this->getAccessibleMock('TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication',
			array('dummy'), array(), '', FALSE);
		$mockFe_user->_set('user', $mockAttributeUser);
		$this->tsfe = $this->getAccessibleMock(
				'\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController',
				array('dummy'), array(), '', FALSE);
		$this->tsfe->_set('fe_user', $mockFe_user);
		$GLOBALS['TSFE'] = $this->tsfe;

		$fixture->expects($this->once())->method('hasLoggedInFrontendUser')
			->will($this->returnValue(TRUE));
		$mockUserRepository->expects($this->once())->method('findOneByUid')
			->with(5)
			->will($this->returnValue($mockUser));

		$this->assertSame(
			$mockUser,
			$fixture->getFrontendUser()
		);
	}

	/**
	 * @test
	 * @covers ::hasLoggedInFrontendUser
	 */
	public function hasLoggedInFrontendReturnsGlobalsLoginUser() {
		$this->tsfe = $this->getAccessibleMock(
				'\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController',
				array('dummy'), array(), '', FALSE);
		$this->tsfe->_set('loginUser', TRUE);
		$GLOBALS['TSFE'] = $this->tsfe;

		$this->assertTrue($this->fixture->hasLoggedInFrontendUser());
	}

	/**
	 * @test
	 * @covers ::hasLoggedInClient
	 */
	public function hasLoggedInClientReturnsFalseIfFrontendUserIsNull() {
		$fixture = $this->getAccessibleMock(
				'\Webfox\Placements\Service\AccessControlService',
				array('getFrontendUser'), array(), '', FALSE);

		$fixture->expects($this->once())->method('getFrontendUser')
			->will($this->returnValue(NULL));

		$this->assertFalse($fixture->hasLoggedInClient());
	}

	/**
	 * @test
	 * @covers ::hasLoggedInClient
	 */
	public function hasLoggedInClientReturnsFalseIfFrontendUserHasNoClient() {
		$fixture = $this->getAccessibleMock(
				'\Webfox\Placements\Service\AccessControlService',
				array('getFrontendUser'), array(), '', FALSE);
		$mockFrontendUser = $this->getMock('\Webfox\Placements\Domain\Model\User',
			array('getClient'), array(), '', FALSE);

		$fixture->expects($this->exactly(2))->method('getFrontendUser')
			->will($this->returnValue($mockFrontendUser));
		$mockFrontendUser->expects($this->once())->method('getClient')
			->will($this->returnValue(NULL));

		$this->assertFalse($fixture->hasLoggedInClient());
	}

	/**
	 * @test
	 * @covers ::hasLoggedInClient
	 */
	public function hasLoggedInClientReturnsTrueIfFrontendUserHasClient() {
		$fixture = $this->getAccessibleMock(
				'\Webfox\Placements\Service\AccessControlService',
				array('getFrontendUser'), array(), '', FALSE);
		$mockFrontendUser = $this->getMock('\Webfox\Placements\Domain\Model\User',
			array('getClient'), array(), '', FALSE);
		$mockClient = $this->getMock('\Webfox\Placements\Domain\Model\Client');

		$fixture->expects($this->exactly(2))->method('getFrontendUser')
			->will($this->returnValue($mockFrontendUser));
		$mockFrontendUser->expects($this->once())->method('getClient')
			->will($this->returnValue($mockClient));

		$this->assertTrue($fixture->hasLoggedInClient());
	}

	/**
	 * @test
	 * @covers ::matchesClient
	 */
	public function matchesClientReturnsFalseIfObjectsClientIsNull() {
		$mockObject = $this->getMock('\Webfox\Placements\Domain\Model\Position',
			array('getClient'), array(), '', FALSE);

		$this->assertFalse($this->fixture->matchesClient($mockObject));
	}

	/**
	 * @test
	 * @covers ::matchesClient
	 */
	public function matchesClientReturnsFalseIfNoClientIsLoggedIn() {
		$fixture = $this->getMock('\Webfox\Placements\Service\AccessControlService',
			array('hasLoggedInClient'), array(), '', FALSE);
		$mockObject = $this->getMock('\Webfox\Placements\Domain\Model\Position',
			array('getClient'), array(), '', FALSE);
		$mockClient = $this->getMock('\Webfox\Placements\Domain\Model\Client');

		$mockObject->expects($this->once())->method('getClient')
			->will($this->returnValue($mockClient));
		$fixture->expects($this->once())->method('hasLoggedInClient')
			->will($this->returnValue(FALSE));
		$this->assertFalse($fixture->matchesClient($mockObject));
	}

	/**
	 * @test
	 * @covers ::matchesClient
	 */
	public function matchesClientReturnsFalseIfClientUidsDoNotMatch() {
		$fixture = $this->getMock('\Webfox\Placements\Service\AccessControlService',
			array('hasLoggedInClient', 'getFrontendUser'), array(), '', FALSE);
		$mockObject = $this->getMock('\Webfox\Placements\Domain\Model\Position',
			array('getClient'), array(), '', FALSE);
		$mockUser = $this->getMock('\Webfox\Placements\Domain\Model\User',
			array('getClient'), array(), '', FALSE);
		$mockClientInObject = $this->getMock('\Webfox\Placements\Domain\Model\Client',
			array('getUid'), array(), '', FALSE);
		$mockClientOfCurrentUser = $this->getMock('\Webfox\Placements\Domain\Model\Client',
			array('getUid'), array(), '', FALSE);

		$mockObject->expects($this->any())->method('getClient')
			->will($this->returnValue($mockClientInObject));
		$fixture->expects($this->once())->method('hasLoggedInClient')
			->will($this->returnValue(TRUE));
		$fixture->expects($this->once())->method('getFrontendUser')
			->will($this->returnValue($mockUser));
		$mockUser->expects($this->once())->method('getClient')
			->will($this->returnValue($mockClientOfCurrentUser));

		$mockClientInObject->expects($this->once())->method('getUid')
			->will($this->returnValue(55));
		$mockClientOfCurrentUser->expects($this->once())->method('getUid')
			->will($this->returnValue(1));

		$this->assertFalse($fixture->matchesClient($mockObject));
	}

	/**
	 * @test
	 * @covers ::matchesClient
	 */
	public function matchesClientReturnsTrueIfClientUidsDoMatch() {
		$fixture = $this->getMock('\Webfox\Placements\Service\AccessControlService',
			array('hasLoggedInClient', 'getFrontendUser'), array(), '', FALSE);
		$mockObject = $this->getMock('\Webfox\Placements\Domain\Model\Position',
			array('getClient'), array(), '', FALSE);
		$mockUser = $this->getMock('\Webfox\Placements\Domain\Model\User',
			array('getClient'), array(), '', FALSE);
		$mockClientInObject = $this->getMock('\Webfox\Placements\Domain\Model\Client',
			array('getUid'), array(), '', FALSE);
		$mockClientOfCurrentUser = $this->getMock('\Webfox\Placements\Domain\Model\Client',
			array('getUid'), array(), '', FALSE);

		$mockObject->expects($this->any())->method('getClient')
			->will($this->returnValue($mockClientInObject));
		$fixture->expects($this->once())->method('hasLoggedInClient')
			->will($this->returnValue(TRUE));
		$fixture->expects($this->once())->method('getFrontendUser')
			->will($this->returnValue($mockUser));
		$mockUser->expects($this->once())->method('getClient')
			->will($this->returnValue($mockClientOfCurrentUser));

		$mockClientInObject->expects($this->once())->method('getUid')
			->will($this->returnValue(55));
		$mockClientOfCurrentUser->expects($this->once())->method('getUid')
			->will($this->returnValue(55));

		$this->assertTrue($fixture->matchesClient($mockObject));
	}

	/**
	 * @test
	 * @covers ::isAllowedToEdit
	 */
	public function isAllowedToEditReturnsFalseIfEditAndAdminNotAllowed() {
		$fixture = $this->getAccessibleMock('\Webfox\Placements\Service\AccessControlService',
			array('isAllowed'), array(), '', FALSE);

		$fixture->expects($this->exactly(2))->method('isAllowed')
			->withConsecutive(
				array('edit', 'foo'),
				array('admin', 'foo'))
			->will($this->onConsecutiveCalls(FALSE, FALSE));

		$this->assertFalse($fixture->isAllowedToEdit('foo'));
	}

	/**
	 * @test
	 * @covers ::isAllowedToEdit
	 */
	public function isAllowedToEditReturnsTrueIfEditAllowed() {
		$fixture = $this->getAccessibleMock('\Webfox\Placements\Service\AccessControlService',
			array('isAllowed'), array(), '', FALSE);

		$fixture->expects($this->once())->method('isAllowed')
			->with('edit', 'foo')
			->will($this->returnValue(TRUE));
		$this->assertTrue($fixture->isAllowedToEdit('foo'));
	}

	/**
	 * @test
	 * @covers ::isAllowedToEdit
	 */
	public function isAllowedToEditReturnsTrueIfAdminAllowedAndEditNotAllowed() {
		$fixture = $this->getAccessibleMock('\Webfox\Placements\Service\AccessControlService',
			array('isAllowed'), array(), '', FALSE);

		$fixture->expects($this->exactly(2))->method('isAllowed')
			->withConsecutive(
				array('edit', 'foo'),
				array('admin', 'foo'))
			->will($this->onConsecutiveCalls(FALSE, TRUE));

		$this->assertTrue($fixture->isAllowedToEdit('foo'));
	}
	//

	/**
	 * @test
	 * @covers ::isAllowedToCreate
	 */
	public function isAllowedToCreateReturnsFalseIfCreateAndAdminNotAllowed() {
		$fixture = $this->getAccessibleMock('\Webfox\Placements\Service\AccessControlService',
			array('isAllowed'), array(), '', FALSE);

		$fixture->expects($this->exactly(2))->method('isAllowed')
			->withConsecutive(
				array('create', 'foo'),
				array('admin', 'foo'))
			->will($this->onConsecutiveCalls(FALSE, FALSE));

		$this->assertFalse($fixture->isAllowedToCreate('foo'));
	}

	/**
	 * @test
	 * @covers ::isAllowedToCreate
	 */
	public function isAllowedToCreateReturnsTrueIfCreateAllowed() {
		$fixture = $this->getAccessibleMock('\Webfox\Placements\Service\AccessControlService',
			array('isAllowed'), array(), '', FALSE);

		$fixture->expects($this->once())->method('isAllowed')
			->with('create', 'foo')
			->will($this->returnValue(TRUE));
		$this->assertTrue($fixture->isAllowedToCreate('foo'));
	}

	/**
	 * @test
	 * @covers ::isAllowedToCreate
	 */
	public function isAllowedToCreateReturnsTrueIfAdminAllowedAndCreateNotAllowed() {
		$fixture = $this->getAccessibleMock('\Webfox\Placements\Service\AccessControlService',
			array('isAllowed'), array(), '', FALSE);

		$fixture->expects($this->exactly(2))->method('isAllowed')
			->withConsecutive(
				array('create', 'foo'),
				array('admin', 'foo'))
			->will($this->onConsecutiveCalls(FALSE, TRUE));

		$this->assertTrue($fixture->isAllowedToCreate('foo'));
	}
}

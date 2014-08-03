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
}

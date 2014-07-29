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
 * Test case for class \Webfox\Placements\Domain\Model\Category.
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
class CategoryTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\Placements\Domain\Model\Category
	 */
	protected $fixture;

	public function setUp() {
		/**
		 * we have to use the object manager for creating in order to make
		 * the dependency injection in model work
		 */
		//$objectManager = new \TYPO3\CMS\Extbase\Object\ObjectManager();
		//$this->fixture = $objectManager->get('Webfox\Placements\Domain\Model\Category');
		$this->fixture = $this->getAccessibleMock('Webfox\Placements\Domain\Model\Category',
				array('dummy'), array(), '', FALSE);
		$categoryRepository = $this->getMock(
				'Webfox\Placements\Domain\Repository\CategoryRepository', 
				array('findAllChildren'), array(), '', FALSE);
		$this->fixture->_set('categoryRepository', $categoryRepository);
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getChildrenReturnsInitialValueForObjectStorage() {
		$result = array();
		$this->fixture->_get('categoryRepository')->expects($this->once())
			->method('findAllChildren')
			->will($this->returnValue(array()));
		$this->assertSame(
			$result,
			$this->fixture->getChildren()
		);
	}
	
}

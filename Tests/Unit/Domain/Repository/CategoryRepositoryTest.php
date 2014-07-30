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
 * Test case for class Webfox\Placements\Domain\Repository\CategoryRepository.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Placements
 *
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @coversDefaultClass \Webfox\Placements\Domain\Repository\CategoryRepository
 */
class CategoryRepositoryTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\Placements\Domain\Repository\CategoryRepository
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getAccessibleMock(
			'\Webfox\Placements\Domain\Repository\CategoryRepository',
			array('dummy'), array(), '', FALSE);
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandReturnsInitiallyEmptyArray() {
		$mockQuery = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query',
				array(), array(), '', FALSE);
		$mockDemand = $this->getMock('Webfox\Placements\Domain\Model\Dto\CategoryDemand');
		$this->assertSame(
			array(),
			$this->fixture->_call('createConstraintsFromDemand', $mockQuery, $mockDemand)
		);
	}

	/**
	 * @test
	 * @covers ::findAllChildren
	 */
	public function findAllChildrenReturnsArrayWithAllChildren() {
		$fixture = $this->getAccessibleMock(
			'\Webfox\Placements\Domain\Repository\CategoryRepository',
			array('createQuery'), array(), '', FALSE);
		$mockQuery = $this->getMock(
			'\TYPO3\CMS\Extbase\Persistence\Generic\QueryInterface',
			array('matching', 'equals', 'setOrderings', 'execute', '__wakeup'), array(), '', FALSE);
		$mockCategory = $this->getMock(
			'\Webfox\Placements\Domain\Model\Category', array(), array(), '', FALSE);
		$mockResult = $this->getMock(
			'\TYPO3\CMS\Extbase\Persistence\Generic\QueryResult',
			array('toArray', '__wakeup'), array(), '', FALSE);

		$fixture->expects($this->once())->method('createQuery')
			->will($this->returnValue($mockQuery));
		$mockQuery->expects($this->once())->method('matching');
		$mockQuery->expects($this->once())->method('equals')
			->with('parent', $mockCategory);
		$mockQuery->expects($this->once())->method('setOrderings')
			->with(array('title' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
		$mockQuery->expects($this->once())->method('execute')
			->will($this->returnValue($mockResult));
		$mockResult->expects($this->once())->method('toArray');

		$fixture->findAllChildren($mockCategory);
	}
}

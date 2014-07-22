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
 * Test case for class Webfox\Placements\Domain\Repository\AbstractDemandedRepository.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Ajax Map
 *
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @coversDefaultClass \Webfox\Placements\Domain\Repository\AbstractDemandedRepository
 */
class AbstractDemandedRepositoryTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\Placements\Domain\Repository\AbstractDemandedRepository
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\Placements\\Domain\\Repository\\AbstractDemandedRepository',
			array('createConstraintsFromDemand'), array(), '', FALSE);
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 * @covers ::createOrderingsFromDemand
	 */
	public function createOrderingsFromDemandReturnsInitiallyEmptyArray() {
		$mockDemand = $this->getMock('Webfox\Placements\Domain\Model\Dto\OrganizationDemand');
		$mockDemand->expects($this->once())
			->method('getOrder');

		$this->assertEquals(
				array(),
				$this->fixture->_call('createOrderingsFromDemand', $mockDemand)
		);
	}

	/**
	 * @test
	 * @covers ::createOrderingsFromDemand
	 */
	public function createOrderingsFromDemandCreatesOrderings() {
		$mockDemand = $this->getMock('Webfox\Placements\Domain\Model\Dto\OrganizationDemand');
		$mockDemand->expects($this->exactly(2))
			->method('getOrder')
			->will($this->returnValue('bar|baz,foo|desc'));

		$this->assertEquals(
				array(
					'bar' => 'ASC',
					'foo' => 'DESC'
				),
				$this->fixture->_call('createOrderingsFromDemand', $mockDemand)
		);
	}

	/**
	 * @test
	 * @covers ::findDemanded
	 */
	public function findDemandedGeneratesQueryAndReturnsQueryResult() {
		$fixture = $this->getAccessibleMock(
			'\Webfox\Placements\Domain\Repository\AbstractDemandedRepository',
			array('generateQuery', 'createConstraintsFromDemand'), array(), '', FALSE);
		$mockDemand = $this->getMock(
			'\Webfox\Placements\Domain\Model\Dto\DemandInterface');
		$mockQuery = $this->getMock(
			'\TYPO3\CMS\Extbase\Persistence\Generic\Query',
			array(), array(), '', FALSE);
		$mockQueryResult = $this->getMock('TYPO3\CMS\Extbase\Persistence\Generic\QueryResult', array(), array(), '', FALSE);

		$fixture->expects($this->once())->method('generateQuery')
			->will($this->returnValue($mockQuery));
		$mockQuery->expects($this->once())->method('execute')
			->will($this->returnValue($mockQueryResult));
		$mockQueryResult->expects($this->once())->method('count')
			->will($this->returnValue(0));

		$this->assertSame(
			$mockQueryResult,
			$fixture->findDemanded($mockDemand)
		);
	}

	/**
	 * @test
	 * @covers ::findDemanded
	 */
	public function findDemandedFiltersByRadius() {
		$fixture = $this->getAccessibleMock(
			'\Webfox\Placements\Domain\Repository\AbstractDemandedRepository',
			array('generateQuery', 'createConstraintsFromDemand', 'filterByRadius'),
			array(), '', FALSE);
		$mockDemand = $this->getMock(
			'\Webfox\Placements\Domain\Model\Dto\DemandInterface',
			array('getRadius', 'getGeoLocation'), array(), '', FALSE);
		$mockQuery = $this->getMock(
			'\TYPO3\CMS\Extbase\Persistence\Generic\Query',
			array(), array(), '', FALSE);
		$mockQueryResult = $this->getMock('TYPO3\CMS\Extbase\Persistence\Generic\QueryResult', array(), array(), '', FALSE);
		$geoLocation = array('foo' => 'bar');

		$fixture->expects($this->once())->method('generateQuery')
			->will($this->returnValue($mockQuery));
		$mockQuery->expects($this->once())->method('execute')
			->will($this->returnValue($mockQueryResult));
		$mockQueryResult->expects($this->once())->method('count')
			->will($this->returnValue(1));
		$mockDemand->expects($this->exactly(2))->method('getRadius')
			->will($this->returnValue(50000));
		$mockDemand->expects($this->exactly(2))->method('getGeoLocation')
			->will($this->returnValue($geoLocation));
		$fixture->expects($this->once())->method('filterByRadius')
			->with($mockQueryResult, $geoLocation, 50)
			->will($this->returnValue($mockQueryResult));

		$this->assertSame(
			$mockQueryResult,
			$fixture->findDemanded($mockDemand)
		);
	}

	/**
	 * @test
	 * @covers ::generateQuery
	 */
	public function generateQueryCreatesQuery() {
		$fixture = $this->getAccessibleMock(
			'\Webfox\Placements\Domain\Repository\AbstractDemandedRepository',
			array('createQuery', 'createConstraintsFromDemand', 'createOrderingsFromDemand'), array(), '', FALSE);
		$mockDemand = $this->getMock(
			'\Webfox\Placements\Domain\Model\Dto\DemandInterface',
			array('getLimit', 'getOffset'), array(), '', FALSE);
		$mockQuery = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query',
			array(
				'getQuerySettings',
				'in',
				'matching',
				'logicalAnd',
				'setOrderings',
				'setLimit',
				'setOffset',
				'__wakeup'
			),
			array(), '', FALSE);
		$mockQuerySettings = $this->getMock(
				'\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings',
				array('getStoragePageIds', 'setRespectEnableFields'), array(), '', FALSE);
		$storagePageIds = array(1,5);
		$orderings = array('foo' => 'bar');
		$limit = 100;
		$offset = 10;
		$constraints = array('baz' => 'foo');
		$fixture->expects($this->once())->method('createQuery')
			->will($this->returnValue($mockQuery));
		$fixture->expects($this->once())->method('createConstraintsFromDemand')
			->with($mockQuery, $mockDemand);
		$mockQuery->expects($this->exactly(2))->method('getQuerySettings')
			->will($this->returnValue($mockQuerySettings));
		$mockQuerySettings->expects($this->once())->method('getStoragePageIds')
			->will($this->returnValue($storagePageIds));
		$mockQuery->expects($this->once())->method('in')
			->with('pid', $storagePageIds)
			->will($this->returnValue($constraints));
		$mockQuerySettings->expects($this->once())->method('setRespectEnableFields')
			->with(FALSE);
		$fixture->expects($this->once())->method('createOrderingsFromDemand')
			->with($mockDemand)->will($this->returnValue($orderings));
		$mockQuery->expects($this->once())->method('logicalAnd')
			->with(array($constraints))
			->will($this->returnValue($constraints));
		$mockQuery->expects($this->once())->method('matching')
			->with($constraints);
		$mockQuery->expects($this->once())->method('setOrderings')
			->with($orderings);
		$mockDemand->expects($this->exactly(2))->method('getLimit')
			->will($this->returnValue($limit));
		$mockQuery->expects($this->once())->method('setLimit')
			->with($limit);
		$mockDemand->expects($this->exactly(2))->method('getOffset')
			->will($this->returnValue($offset));
		$mockQuery->expects($this->once())->method('setOffset')
			->with($offset);

		$fixture->_call('generateQuery', $mockDemand, FALSE);
	}

	/**
	 * @test
	 * @covers ::generateQuery
	 */
	public function generateQueryRespectsEnableFields() {
		$fixture = $this->getAccessibleMock(
			'\Webfox\Placements\Domain\Repository\AbstractDemandedRepository',
			array('createQuery', 'createConstraintsFromDemand', 'createOrderingsFromDemand'), array(), '', FALSE);
		$mockDemand = $this->getMock(
			'\Webfox\Placements\Domain\Model\Dto\DemandInterface',
			array('getLimit', 'getOffset'), array(), '', FALSE);
		$mockQuery = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query',
			array(
				'getQuerySettings',
				'in',
				'equals',
				'matching',
				'logicalAnd',
				'setOrderings',
				'setLimit',
				'setOffset',
				'__wakeup'
			),
			array(), '', FALSE);
		$mockQuerySettings = $this->getMock(
				'\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings',
				array('getStoragePageIds', 'setRespectEnableFields'), array(), '', FALSE);
		$storagePageIds = array(1,5);
		$orderings = array('foo' => 'bar');
		$limit = 100;
		$offset = 10;
		$fixture->expects($this->once())->method('createQuery')
			->will($this->returnValue($mockQuery));
		$fixture->expects($this->once())->method('createConstraintsFromDemand')
			->with($mockQuery, $mockDemand);
		$mockQuery->expects($this->once())->method('getQuerySettings')
			->will($this->returnValue($mockQuerySettings));
		$mockQuerySettings->expects($this->once())->method('getStoragePageIds')
			->will($this->returnValue($storagePageIds));
		$mockQuery->expects($this->once())->method('in')
			->with('pid', $storagePageIds);
		$fixture->expects($this->once())->method('createOrderingsFromDemand')
			->with($mockDemand)->will($this->returnValue($orderings));
		$mockQuery->expects($this->exactly(2))->method('equals')
			->with($this->logicalOr(
						$this->equalTo('deleted', 0),
						$this->equalTo('hidden', 0)
				));
		$mockQuery->expects($this->once())->method('setOrderings')
			->with($orderings);
		$mockDemand->expects($this->exactly(2))->method('getLimit')
			->will($this->returnValue($limit));
		$mockQuery->expects($this->once())->method('setLimit')
			->with($limit);
		$mockDemand->expects($this->exactly(2))->method('getOffset')
			->will($this->returnValue($offset));
		$mockQuery->expects($this->once())->method('setOffset')
			->with($offset);

		$fixture->_call('generateQuery', $mockDemand);
	}

	/**
	 * @test
	 * @covers ::filterByRadius
	 */
	public function filterByRadiusReturnsInitiallyEmptyArray() {
		$fixture = $this->getAccessibleMock(
			'Webfox\\Placements\\Domain\\Repository\\AbstractDemandedRepository',
			array('findMultipleByUid', 'createConstraintsFromDemand'), array(), '', FALSE);
		$mockQueryResult = $this->getMock('TYPO3\CMS\Extbase\Persistence\Generic\QueryResult', array(), array(), '', FALSE);
		$mockQuery = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Query',
			array(), array(), '', FALSE);
		$mockGeoCoder = $this->getMock('Webfox\\Placements\\Utility\\Geocoder', array('distance'));

		$fixture->_set('geoCoder', $mockGeoCoder);
		$mockQueryResult->expects($this->once())->method('toArray')
			->will($this->returnValue(array()));
		$mockQueryResult->expects($this->once())->method('getQuery')
			->will($this->returnValue($mockQuery));
		$mockQuery->expects($this->once())->method('getOrderings')
			->will($this->returnValue(array('foo' => 'ASC')));
		$fixture->expects($this->once())->method('findMultipleByUid')
			->will($this->returnValue($mockQueryResult));
		$fixture->filterByRadius($mockQueryResult, array(), 1000);
	}

	/**
	 * @test
	 * @covers ::filterByRadius
	 */
	public function filterByRadiusReturnsFilteredResult() {
		$fixture = $this->getAccessibleMock(
			'Webfox\\Placements\\Domain\\Repository\\AbstractDemandedRepository',
			array('findMultipleByUid', 'createConstraintsFromDemand'), array(), '', FALSE);
		$mockQueryResult = $this->getMock('TYPO3\CMS\Extbase\Persistence\Generic\QueryResult', array(), array(), '', FALSE);
		$mockQuery = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Query',
			array('__wakeup', 'getOrderings'), array(), '', FALSE);
		$mockGeoCoder = $this->getMock('Webfox\\Placements\\Utility\\Geocoder', array('distance'));
		$fixture->_set('geoCoder', $mockGeoCoder);

		$mockObjectWithinRadius = $this->getMock(
			'Webfox\Placements\Domain\Model\Position',
			array('getLatitude', 'getLongitude', 'getUid'), array(), '', FALSE);
		$mockObjectBeyondRadius = $this->getMock(
			'Webfox\Placements\Domain\Model\Position',
			array('getLatitude', 'getLongitude', 'getUid'), array(), '', FALSE);
		$mockObjectWithinRadius->expects($this->once())
			->method('getLatitude')
			->will($this->returnValue(1.2));
		$mockObjectWithinRadius->expects($this->once())
			->method('getLongitude')
			->will($this->returnValue(3.4));

		$mockObjectBeyondRadius->expects($this->once())
			->method('getLatitude')
			->will($this->returnValue(5.6));
		$mockObjectBeyondRadius->expects($this->once())
			->method('getLongitude')
			->will($this->returnValue(7.8));

		$mockGeoCoder->expects($this->exactly(2))->method('distance')
			->will($this->onConsecutiveCalls(500, 5000));
		$mockObjectWithinRadius->expects($this->once())
			->method('getUid')->will($this->returnValue(1));
		$mockObjectBeyondRadius->expects($this->never())
			->method('getUid');
		$mockQueryResult->expects($this->once())->method('toArray')
			->will($this->returnValue(array($mockObjectWithinRadius, $mockObjectBeyondRadius)));
		$mockQueryResult->expects($this->once())->method('getQuery')
			->will($this->returnValue($mockQuery));
		$mockQuery->expects($this->once())->method('getOrderings')
			->will($this->returnValue(array('foo' => 'ASC')));
		$fixture->expects($this->once())->method('findMultipleByUid')
			->with(1, 'foo', 'ASC')
			->will($this->returnValue($mockQueryResult));
		$fixture->filterByRadius($mockQueryResult, array(), 1000);
	}

	/**
	 * @test
	 * @covers ::findMultipleByUid
	 */
	public function findMultipleByUidCreatesQuery () {
		$fixture = $this->getAccessibleMock(
			'\Webfox\Placements\Domain\Repository\AbstractDemandedRepository',
			array('createQuery', 'createConstraintsFromDemand'), array(), '', FALSE);
		$recordList = '1,5,7';
		$mockQuery = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query',
			array(
				'matching',
				'in',
				'execute',
				'setOrderings',
				'__wakeup'
			), array(), '', FALSE);

		$fixture->expects($this->once())->method('createQuery')
			->will($this->returnValue($mockQuery));
		$mockQuery->expects($this->once())->method('matching');
		$mockQuery->expects($this->once())->method('in')
			->with('uid', array(1,5,7));
		$mockQuery->expects($this->once())->method('setOrderings');

		$mockQuery->expects($this->once())->method('execute');
		$fixture->findMultipleByUid($recordList);
	}

	/**
	 * @test
	 * @covers ::findMultipleByUid
	 */
	public function findMultipleByUidSetsDefaultOrderings () {
		$fixture = $this->getAccessibleMock(
			'\Webfox\Placements\Domain\Repository\AbstractDemandedRepository',
			array('createQuery', 'createConstraintsFromDemand'), array(), '', FALSE);
		$recordList = '1,5,7';
		$mockQuery = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query',
			array(
				'__wakeup',
				'matching',
				'in',
				'execute',
				'setOrderings'
			), array(), '', FALSE);

		$fixture->expects($this->once())->method('createQuery')
			->will($this->returnValue($mockQuery));
		$mockQuery->expects($this->once())->method('setOrderings')
			->with(array('uid' => 'ASC'));

		$fixture->findMultipleByUid($recordList);
	}
}
?>

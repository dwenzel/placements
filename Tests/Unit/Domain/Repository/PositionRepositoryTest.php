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
 * Test case for class Webfox\Placements\Domain\Repository\PositionRepository.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Placements
 *
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @coversDefaultClass \Webfox\Placements\Domain\Repository\PositionRepository
 */
class PositionRepositoryTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\Placements\Domain\Repository\PositionRepository
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = $this->getAccessibleMock(
			'Webfox\\Placements\\Domain\\Repository\\PositionRepository',
			array('dummy'), array(), '', FALSE);
		$this->fixture->_set('objectManager',
				$this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManagerInterface'));
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandReturnsInitiallyEmptyConstraint() {
		$mockQuery = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query',
				array(), array(), '', FALSE);
		$mockDemand = $this->getMock('Webfox\Placements\Domain\Model\Dto\PositionDemand');
		$this->assertSame(
			array(),
			$this->fixture->_call('createConstraintsFromDemand', $mockQuery, $mockDemand)
		);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCreatesPositionTypeConstraintsWithDefaultConjunction() {
		$query = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query',
			array('__wakeup', 'equals', 'logicalAnd'), array(), '', FALSE);

		$demand = $this->getMock(
				'\Webfox\Placements\Domain\Model\Dto\PositionDemand',
				array('getConstraintsConjunction', 'getPositionTypes'), array(), '', FALSE);
		$demandedValue = '1,3,5';

		//return null for default conjunction
		$demand->expects($this->any())->method('getConstraintsConjunction');
		$demand->expects($this->any())->method('getPositionTypes')
			->will($this->returnValue($demandedValue));

		$query->expects($this->exactly(3))->method('equals')
			->with($this->logicalOr(
						$this->equalTo('type.uid', 1),
						$this->equalTo('type.uid', 3),
						$this->equalTo('type.uid', 5)
				))
			->will($this->returnValue('foo'));
		
		// default conjunction is 'and'
		$query->expects($this->once())->method('logicalAnd')
			->with(array('foo', 'foo', 'foo'));

		$this->fixture->_call('createConstraintsFromDemand',$query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCreatesPositionTypeConstraintsWithConjunctionOr() {
		$query = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query',
			array('__wakeup', 'equals', 'logicalOr'), array(), '', FALSE);

		$demand = $this->getMock(
				'\Webfox\Placements\Domain\Model\Dto\PositionDemand',
				array('getConstraintsConjunction', 'getPositionTypes'), array(), '', FALSE);
		$demandedValue = '1,3,5';

		$demand->expects($this->any())->method('getConstraintsConjunction')
			->will($this->returnValue('or'));
		$demand->expects($this->any())->method('getPositionTypes')
			->will($this->returnValue($demandedValue));

		$query->expects($this->exactly(3))->method('equals')
			->with($this->logicalOr(
						$this->equalTo('type.uid', 1),
						$this->equalTo('type.uid', 3),
						$this->equalTo('type.uid', 5)
				))
			->will($this->returnValue('foo'));
		
		// default conjunction is 'and'
		$query->expects($this->once())->method('logicalOr')
			->with(array('foo', 'foo', 'foo'));

		$this->fixture->_call('createConstraintsFromDemand',$query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCreatesWorkingHoursConstraintsWithDefaultConjunction() {
		$query = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query',
			array('__wakeup', 'equals', 'logicalAnd'), array(), '', FALSE);

		$demand = $this->getMock(
				'\Webfox\Placements\Domain\Model\Dto\PositionDemand',
				array('getConstraintsConjunction', 'getWorkingHours'), array(), '', FALSE);
		$demandedValue = '1,3,5';

		//return null for default conjunction
		$demand->expects($this->any())->method('getConstraintsConjunction');
		$demand->expects($this->any())->method('getWorkingHours')
			->will($this->returnValue($demandedValue));

		$query->expects($this->exactly(3))->method('equals')
			->with($this->logicalOr(
						$this->equalTo('workingHours.uid', 1),
						$this->equalTo('workingHours.uid', 3),
						$this->equalTo('workingHours.uid', 5)
				))
			->will($this->returnValue('foo'));
		
		// default conjunction is 'and'
		$query->expects($this->once())->method('logicalAnd')
			->with(array('foo', 'foo', 'foo'));

		$this->fixture->_call('createConstraintsFromDemand',$query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCreatesWorkingHoursConstraintsWithConjunctionOr() {
		$query = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query',
			array('__wakeup', 'equals', 'logicalOr'), array(), '', FALSE);

		$demand = $this->getMock(
				'\Webfox\Placements\Domain\Model\Dto\PositionDemand',
				array('getConstraintsConjunction', 'getWorkingHours'), array(), '', FALSE);
		$demandedValue = '1,3,5';

		$demand->expects($this->any())->method('getConstraintsConjunction')
			->will($this->returnValue('or'));
		$demand->expects($this->any())->method('getWorkingHours')
			->will($this->returnValue($demandedValue));

		$query->expects($this->exactly(3))->method('equals')
			->with($this->logicalOr(
						$this->equalTo('workingHours.uid', 1),
						$this->equalTo('workingHours.uid', 3),
						$this->equalTo('workingHours.uid', 5)
				))
			->will($this->returnValue('foo'));
		
		$query->expects($this->once())->method('logicalOr')
			->with(array('foo', 'foo', 'foo'));

		$this->fixture->_call('createConstraintsFromDemand',$query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCreatesSectorsConstraintsWithDefaultConjunction() {
		$query = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query',
			array('__wakeup', 'equals', 'logicalAnd'), array(), '', FALSE);

		$demand = $this->getMock(
				'\Webfox\Placements\Domain\Model\Dto\PositionDemand',
				array('getConstraintsConjunction', 'getSectors'), array(), '', FALSE);
		$demandedValue = '1,3,5';

		//return null for default conjunction
		$demand->expects($this->any())->method('getConstraintsConjunction');
		$demand->expects($this->any())->method('getSectors')
			->will($this->returnValue($demandedValue));

		$query->expects($this->exactly(3))->method('equals')
			->with($this->logicalOr(
						$this->equalTo('sectors.uid', 1),
						$this->equalTo('sectors.uid', 3),
						$this->equalTo('sectors.uid', 5)
				))
			->will($this->returnValue('foo'));
		
		// default conjunction is 'and'
		$query->expects($this->once())->method('logicalAnd')
			->with(array('foo', 'foo', 'foo'));

		$this->fixture->_call('createConstraintsFromDemand',$query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCreatesSectorsConstraintsWithConjunctionOr() {
		$query = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query',
			array('__wakeup', 'equals', 'logicalOr'), array(), '', FALSE);

		$demand = $this->getMock(
				'\Webfox\Placements\Domain\Model\Dto\PositionDemand',
				array('getConstraintsConjunction', 'getSectors'), array(), '', FALSE);
		$demandedValue = '1,3,5';

		$demand->expects($this->any())->method('getConstraintsConjunction')
			->will($this->returnValue('or'));
		$demand->expects($this->any())->method('getSectors')
			->will($this->returnValue($demandedValue));

		$query->expects($this->exactly(3))->method('equals')
			->with($this->logicalOr(
						$this->equalTo('sectors.uid', 1),
						$this->equalTo('sectors.uid', 3),
						$this->equalTo('sectors.uid', 5)
				))
			->will($this->returnValue('foo'));
		
		$query->expects($this->once())->method('logicalOr')
			->with(array('foo', 'foo', 'foo'));

		$this->fixture->_call('createConstraintsFromDemand',$query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCreatesSearchConstraintsWithConjunctionOr() {
		$query = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query',
			array('__wakeup', 'like', 'logicalOr'), array(), '', FALSE);

		$demand = $this->getMock(
			'\Webfox\Placements\Domain\Model\Dto\PositionDemand',
			array('getSearch', 'getSubjet'), array(), '', FALSE);
		$mockSearch = $this->getMock(
			'\Webfox\Placements\Domain\Model\Dto\Search',
			array('getSubject', 'getFields'), array(), '', FALSE);
		$subject = 'boo';
		$searchFields = 'foo,bar,baz';

		$demand->expects($this->any())->method('getSearch')
			->will($this->returnValue($mockSearch));

		$mockSearch->expects($this->once())->method('getSubject')
			->will($this->returnValue($subject));
		$mockSearch->expects($this->once())->method('getFields')
			->will($this->returnValue($searchFields));

		$query->expects($this->exactly(3))->method('like')
			->withConsecutive(
					array('foo', '%boo%'),
					array('bar', '%boo%'),
					array('baz', '%boo%')
				)
			->will($this->returnValue('foo'));
		
		// default conjunction is 'and'
		$query->expects($this->once())->method('logicalOr')
			->with(array('foo', 'foo', 'foo'));

		$this->fixture->_call('createConstraintsFromDemand',$query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 * @expectedException \UnexpectedValueException
	 * @expectedExceptionCode 1382608407
	 * @expectedExceptionMessage No search fields given
	 */
	public function createConstraintsFromDemandThrowsExceptionForMissingSearchFields() {
		$query = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query',
			array('__wakeup'), array(), '', FALSE);

		$demand = $this->getMock(
			'\Webfox\Placements\Domain\Model\Dto\PositionDemand',
			array('getSearch', 'getSubjet'), array(), '', FALSE);
		$mockSearch = $this->getMock(
			'\Webfox\Placements\Domain\Model\Dto\Search',
			array('getSubject', 'getFields'), array(), '', FALSE);
		$subject = 'foo';
		$searchFields = '';

		$demand->expects($this->any())->method('getSearch')
			->will($this->returnValue($mockSearch));

		$mockSearch->expects($this->once())->method('getSubject')
			->will($this->returnValue($subject));
		$mockSearch->expects($this->once())->method('getFields')
			->will($this->returnValue($searchFields));

		$this->fixture->_call('createConstraintsFromDemand',$query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCreatesLocationConstraintsForBoundsWithConjunctionAnd() {
		$query = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query',
			array('__wakeup', 'lessThan', 'greaterThan', 'logicalAnd'), array(), '', FALSE);

		$demand = $this->getMock(
			'\Webfox\Placements\Domain\Model\Dto\PositionDemand',
			array('getSearch', 'getLocation'), array(), '', FALSE);
		$mockSearch = $this->getMock(
			'\Webfox\Placements\Domain\Model\Dto\Search',
			array('getLocation', 'getBounds', 'getRadius'), array(), '', FALSE);
		$bounds = array(
				'N' => array('lat' => 1.5, 'lng' => 2.5),
				'S' => array('lat' => 3.5, 'lng' => 4.5),
				'W' => array('lat' => 5.5, 'lng' => 6.5),
				'E' => array('lat' => 7.5, 'lng' => 8.5)
		);

		$demand->expects($this->any())->method('getSearch')
			->will($this->returnValue($mockSearch));

		$mockSearch->expects($this->once())->method('getBounds')
			->will($this->returnValue($bounds));
		$mockSearch->expects($this->once())->method('getLocation');
		$mockSearch->expects($this->once())->method('getRadius');

		$query->expects($this->exactly(2))->method('greaterThan')
			->withConsecutive(
					array('latitude', $bounds['S']['lat']),
					array('longitude', $bounds['W']['lng'])
				)
			->will($this->onConsecutiveCalls('S', 'W'));
		$query->expects($this->exactly(2))->method('lessThan')
			->withConsecutive(
					array('latitude', $bounds['N']['lat']),
					array('longitude', $bounds['E']['lng'])
				)
			->will($this->onConsecutiveCalls('N', 'E'));

		$query->expects($this->once())->method('logicalAnd')
			->with(array('S', 'N', 'W', 'E'));

		$this->fixture->_call('createConstraintsFromDemand',$query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCreatesLocationConstraintsForLocationAndRadiusWithConjunctionAnd() {
		$query = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query',
			array('__wakeup', 'lessThan', 'greaterThan', 'logicalAnd'), array(), '', FALSE);

		$demand = $this->getMock(
			'\Webfox\Placements\Domain\Model\Dto\PositionDemand',
			array('getSearch', 'getLocation'), array(), '', FALSE);
		$mockSearch = $this->getMock(
			'\Webfox\Placements\Domain\Model\Dto\Search',
			array('getLocation', 'getBounds', 'getRadius'), array(), '', FALSE);
		$mockGeoCoder = $this->getMock(
			'\Webfox\Placements\Utility\Geocoder',
			array('getLocation', 'getBoundsByRadius'), array(), '', FALSE);
		$this->fixture->_set('geoCoder', $mockGeoCoder);
		$bounds = array(
				'N' => array('lat' => 1.5, 'lng' => 2.5),
				'S' => array('lat' => 3.5, 'lng' => 4.5),
				'W' => array('lat' => 5.5, 'lng' => 6.5),
				'E' => array('lat' => 7.5, 'lng' => 8.5)
		);
		$location = array('lat' => 9.3, 'lng' => 10.5);
		$radius = 50000;

		$demand->expects($this->any())->method('getSearch')
			->will($this->returnValue($mockSearch));

		$mockSearch->expects($this->once())->method('getBounds')
			->will($this->returnValue(NULL));
		$mockSearch->expects($this->once())->method('getLocation')
			->will($this->returnValue($location));
		$mockSearch->expects($this->once())->method('getRadius')
			->will($this->returnValue($radius));

		$mockGeoCoder->expects($this->once())->method('getBoundsByRadius')
			->with($location['lat'], $location['lng'], $radius/1000)
			->will($this->returnValue($bounds));
		$query->expects($this->exactly(2))->method('greaterThan')
			->withConsecutive(
					array('latitude', $bounds['S']['lat']),
					array('longitude', $bounds['W']['lng'])
				)
			->will($this->onConsecutiveCalls('S', 'W'));
		$query->expects($this->exactly(2))->method('lessThan')
			->withConsecutive(
					array('latitude', $bounds['N']['lat']),
					array('longitude', $bounds['E']['lng'])
				)
			->will($this->onConsecutiveCalls('N', 'E'));

		$query->expects($this->once())->method('logicalAnd')
			->with(array('S', 'N', 'W', 'E'));

		$this->fixture->_call('createConstraintsFromDemand',$query, $demand);
	}

	/**
	 * @test
	 * @covers ::createConstraintsFromDemand
	 */
	public function createConstraintsFromDemandCreatesClientsConstraints() {
		$query = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query',
			array('__wakeup', 'equals', 'logicalOr'), array(), '', FALSE);

		$demand = $this->getMock(
				'\Webfox\Placements\Domain\Model\Dto\PositionDemand',
				array('getClients'), array(), '', FALSE);
		$clients = '1,3,5';

		$demand->expects($this->any())->method('getClients')
			->will($this->returnValue($clients));

		$query->expects($this->exactly(3))->method('equals')
			->with($this->logicalOr(
						$this->equalTo('client.uid', 1),
						$this->equalTo('client.uid', 3),
						$this->equalTo('client.uid', 5)
				))
			->will($this->returnValue('foo'));
		
		$query->expects($this->once())->method('logicalOr')
			->with(array('foo', 'foo', 'foo'));

		$this->fixture->_call('createConstraintsFromDemand',$query, $demand);
	}

	/**
	 * @test
	 */
	public function dummy() {
		$this->markTestIncomplete();	
	}
}
?>

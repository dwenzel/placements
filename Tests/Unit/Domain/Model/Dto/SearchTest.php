<?php

namespace Webfox\Placements\Tests;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Dirk Wenzel <wenzel@webfox01.de>, AgenturWebfox GmbH
 *  			Michael Kasten <kasten@webfox01.de>, AgenturWebfox GmbH
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
 * Test case for class \Webfox\Placements\Domain\Model\Dto\Search.
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
class SearchTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\Placements\Domain\Model\Dto\Search
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \Webfox\Placements\Domain\Model\Dto\Search();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getSubjectReturnsInitialValueForString() {
		$this->assertNull($this->fixture->getSubject());
	}

	/**
	 * @test
	 */
	public function setSubjectForStringSetsSubject() {
		$this->fixture->setSubject('ping');
		$this->assertSame('ping', $this->fixture->getSubject());
	}
	
	/**
	 * @test
	 */
	public function getFieldsReturnsInitialValueForString() { 
		$this->assertNull($this->fixture->getFields());
	}

	/**
	 * @test
	 */
	public function setFieldsForStringSetsFields() { 
		$this->fixture->setFields('ping');
		$this->assertSame('ping', $this->fixture->getFields());
	}

	/**
	 * @test
	 */
	public function getLocationReturnsInitialValueForString() { 
		$this->assertNull($this->fixture->getLocation());
	}

	/**
	 * @test
	 */
	public function setLocationForStringSetsLocation() { 
		$this->fixture->setLocation('ping');
		$this->assertSame('ping', $this->fixture->getLocation());
	}
	
	/**
	 * @test
	 */
	public function getRadiusReturnsInitialValueForInteger() { 
		$this->assertNull($this->fixture->getRadius());
	}

	/**
	 * @test
	 */
	public function setRadiusForIntegerSetsRadius() { 
		$this->fixture->setRadius(5000);
		$this->assertSame(5000, $this->fixture->getRadius());
	}
	
	/**
	 * @test
	 */
	public function getBoundsReturnsInitialValueForArray() { 
		$this->assertNull($this->fixture->getBounds());
	}

	/**
	 * @test
	 */
	public function setBoundsForArraySetsBounds() { 
		$bounds = array('test' => 'value');
		$this->fixture->setBounds($bounds);
		$this->assertSame($bounds, $this->fixture->getBounds());
	}
	
}
?>

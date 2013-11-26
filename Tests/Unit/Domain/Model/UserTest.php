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
 * Test case for class \Webfox\Placements\Domain\Model\User.
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
class UserTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Webfox\Placements\Domain\Model\User
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \Webfox\Placements\Domain\Model\User();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
 	 * @test
 	 */
	public function getClientReturnsInitialValueForClient() {
		$this->assertNull(
			$this->fixture->getClient()
		);
	}

	/**
	 * @test
	 */
	public function setClientForClientSetsClient() {
		$client = new \Webfox\Placements\Domain\Model\Client();
		$this->fixture->setClient($client);
		$this->assertSame(
			$client,
			$this->fixture->getClient()
		);
	}
	/**
	 * @test
	 */
	public function getResumesReturnsInitialValueForResume() { 
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->fixture->getResumes()
		);
	}

	/**
	 * @test
	 */
	public function setResumesForObjectStorageContainingResumeSetsResumes() { 
		$resume = new \Webfox\Placements\Domain\Model\Resume();
		$objectStorageHoldingExactlyOneResumes = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneResumes->attach($resume);
		$this->fixture->setResumes($objectStorageHoldingExactlyOneResumes);

		$this->assertSame(
			$objectStorageHoldingExactlyOneResumes,
			$this->fixture->getResumes()
		);
	}
	
	/**
	 * @test
	 */
	public function addResumeToObjectStorageHoldingResumes() {
		$resume = new \Webfox\Placements\Domain\Model\Resume();
		$objectStorageHoldingExactlyOneResume = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneResume->attach($resume);
		$this->fixture->addResume($resume);

		$this->assertEquals(
			$objectStorageHoldingExactlyOneResume,
			$this->fixture->getResumes()
		);
	}

	/**
	 * @test
	 */
	public function removeResumeFromObjectStorageHoldingResumes() {
		$resume = new \Webfox\Placements\Domain\Model\Resume();
		$localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$localObjectStorage->attach($resume);
		$localObjectStorage->detach($resume);
		$this->fixture->addResume($resume);
		$this->fixture->removeResume($resume);

		$this->assertEquals(
			$localObjectStorage,
			$this->fixture->getResumes()
		);
	}
	
	/**
	 * @test
	 */
	public function getApplicationsReturnsInitialValueForApplication() { 
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->fixture->getApplications()
		);
	}

	/**
	 * @test
	 */
	public function setApplicationsForObjectStorageContainingApplicationSetsApplications() { 
		$application = new \Webfox\Placements\Domain\Model\Application();
		$objectStorageHoldingExactlyOneApplications = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneApplications->attach($application);
		$this->fixture->setApplications($objectStorageHoldingExactlyOneApplications);

		$this->assertSame(
			$objectStorageHoldingExactlyOneApplications,
			$this->fixture->getApplications()
		);
	}
	
	/**
	 * @test
	 */
	public function addApplicationToObjectStorageHoldingApplications() {
		$application = new \Webfox\Placements\Domain\Model\Application();
		$objectStorageHoldingExactlyOneApplication = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneApplication->attach($application);
		$this->fixture->addApplication($application);

		$this->assertEquals(
			$objectStorageHoldingExactlyOneApplication,
			$this->fixture->getApplications()
		);
	}

	/**
	 * @test
	 */
	public function removeApplicationFromObjectStorageHoldingApplications() {
		$application = new \Webfox\Placements\Domain\Model\Application();
		$localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$localObjectStorage->attach($application);
		$localObjectStorage->detach($application);
		$this->fixture->addApplication($application);
		$this->fixture->removeApplication($application);

		$this->assertEquals(
			$localObjectStorage,
			$this->fixture->getApplications()
		);
	}
	
}
?>

<?php
namespace Webfox\Placements\Domain\Model;
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
 *
 *
 * @package placements
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class User extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser {

	/**
	 * Client
	 * 
	 * @var \Webfox\Placements\Domain\Model\Client
	 */
	protected $client;

	/**
	 * resumes
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Webfox\Placements\Domain\Model\Resume>
	 * @lazy
	 */
	protected $resumes;

	/**
	 * applications
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Webfox\Placements\Domain\Model\Application>
	 * @lazy
	 */
	protected $applications;

	/**
	 * __construct
	 *
	 * @return User
	 */
	public function __construct() {
		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * Initializes all ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		/**
		 * Do not modify this method!
		 * It will be rewritten on each save in the extension builder
		 * You may modify the constructor of this class instead
		 */
		$this->resumes = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		
		$this->applications = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

	/**
	 * Get the client
	 *
	 * @return \Webfox\Placements\Domain\Model\Client
	 */
	public function getClient() {
		return $this->client;
	}

	/**
	 * Sets the client
	 * 
	 * @param \Webfox\Placements\Domain\Model\Client $client
	 */
	public function setClient($client) {
		$this->client = $client;
	}

	/**
	 * Adds a Resume
	 *
	 * @param \Webfox\Placements\Domain\Model\Resume $resume
	 * @return void
	 */
	public function addResume(\Webfox\Placements\Domain\Model\Resume $resume) {
		$this->resumes->attach($resume);
	}

	/**
	 * Removes a Resume
	 *
	 * @param \Webfox\Placements\Domain\Model\Resume $resumeToRemove The Resume to be removed
	 * @return void
	 */
	public function removeResume(\Webfox\Placements\Domain\Model\Resume $resumeToRemove) {
		$this->resumes->detach($resumeToRemove);
	}

	/**
	 * Returns the resumes
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Webfox\Placements\Domain\Model\Resume> $resumes
	 */
	public function getResumes() {
		return $this->resumes;
	}

	/**
	 * Sets the resumes
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Webfox\Placements\Domain\Model\Resume> $resumes
	 * @return void
	 */
	public function setResumes(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $resumes) {
		$this->resumes = $resumes;
	}

	/**
	 * Adds a Application
	 *
	 * @param \Webfox\Placements\Domain\Model\Application $application
	 * @return void
	 */
	public function addApplication(\Webfox\Placements\Domain\Model\Application $application) {
		$this->applications->attach($application);
	}

	/**
	 * Removes a Application
	 *
	 * @param \Webfox\Placements\Domain\Model\Application $applicationToRemove The Application to be removed
	 * @return void
	 */
	public function removeApplication(\Webfox\Placements\Domain\Model\Application $applicationToRemove) {
		$this->applications->detach($applicationToRemove);
	}

	/**
	 * Returns the applications
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Webfox\Placements\Domain\Model\Application> $applications
	 */
	public function getApplications() {
		return $this->applications;
	}

	/**
	 * Sets the applications
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Webfox\Placements\Domain\Model\Application> $applications
	 * @return void
	 */
	public function setApplications(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $applications) {
		$this->applications = $applications;
	}

}

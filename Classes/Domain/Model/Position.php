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
class Position extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
implements GeocodingInterface {

	/**
	 * title
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $title;

	/**
	 * Unique ID for position (external)
	 *
	 * @var \string
	 */
	protected $identifier;

	/**
	 * summary
	 *
	 * @var \string
	 */
	protected $summary;

	/**
	 * description
	 *
	 * @var \string
	 */
	protected $description;

	/**
	 * entryDate
	 *
	 * @var \DateTime
	 * @validate DateTime
	 */
	protected $entryDate;

	/**
	 * fixedTerm
	 *
	 * @var boolean
	 */
	protected $fixedTerm = FALSE;

	/**
	 * duration
	 *
	 * @var \string
	 */
	protected $duration;

	/**
	 * zip
	 *
	 * @var \string
	 */
	protected $zip;

	/**
	 * city
	 *
	 * @var \string
	 */
	protected $city;

	/**
	 * @var float
	 */
	protected $latitude;

	/**
	 * @var float
	 */
	protected $longitude;

	/**
	 * payment
	 *
	 * @var \string
	 */
	protected $payment;

	/**
	 * contact
	 *
	 * @var \string
	 */
	protected $contact;

	/**
	 * link
	 *
	 * @var \string
	 */
	protected $link;

	/**
	 * Organization offering this position
	 *
	 * @var \Webfox\Placements\Domain\Model\Organization
	 * @validate NotEmpty
	 * @lazy
	 */
	protected $organization;

	/**
	 * Client for whom this position is managed
	 *
	 * @var \Webfox\Placements\Domain\Model\Client
	 * @lazy
	 */
	protected $client;

	/**
	 * Type of position
	 *
	 * @var \Webfox\Placements\Domain\Model\PositionType
	 * @lazy
	 */
	protected $type;

	/**
	 * Categories
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
	 * @lazy
	 */
	protected $categories;

	/**
	 * Working Hours
	 *
	 * @var \Webfox\Placements\Domain\Model\WorkingHours
	 * @lazy
	 */
	protected $workingHours;

	/**
	 * Sectors
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Webfox\Placements\Domain\Model\Sector>
	 * @lazy
	 */
	protected $sectors;

	/**
	 * export enabled
	 *
	 * @var boolean
	 */
	protected $exportEnabled = FALSE;

	/**
	 * Returns the title
	 *
	 * @return \string $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets the title
	 *
	 * @param \string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Returns the identifier
	 *
	 * @return \string $identifier
	 */
	public function getIdentifier() {
		return $this->identifier;
	}

	/**
	 * Sets the identifier
	 *
	 * @param \string $identifier
	 * @return void
	 */
	public function setIdentifier($identifier) {
		$this->identifier = $identifier;
	}

	/**
	 * Returns the summary
	 *
	 * @return \string $summary
	 */
	public function getSummary() {
		return $this->summary;
	}

	/**
	 * Sets the summary
	 *
	 * @param \string $summary
	 * @return void
	 */
	public function setSummary($summary) {
		$this->summary = $summary;
	}

	/**
	 * Returns the description
	 *
	 * @return \string $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Sets the description
	 *
	 * @param \string $description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Returns the organization
	 *
	 * @return \Webfox\Placements\Domain\Model\Organization $organization
	 */
	public function getOrganization() {
		return $this->organization;
	}

	/**
	 * Sets the organization
	 *
	 * @param \Webfox\Placements\Domain\Model\Organization $organization
	 * @return void
	 */
	public function setOrganization(\Webfox\Placements\Domain\Model\Organization $organization) {
		$this->organization = $organization;
	}

	/**
	 * Returns the client
	 *
	 * @return \Webfox\Placements\Domain\Model\Client $client
	 */
	public function getClient() {
		return $this->client;
	}

	/**
	 * Sets the client
	 *
	 * @param \Webfox\Placements\Domain\Model\Client $client
	 * @return void
	 */
	public function setClient(\Webfox\Placements\Domain\Model\Client $client) {
		$this->client = $client;
	}

	/**
	 * Returns the type
	 *
	 * @return \Webfox\Placements\Domain\Model\PositionType $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Sets the type
	 *
	 * @param \Webfox\Placements\Domain\Model\PositionType $type
	 * @return void
	 */
	public function setType(\Webfox\Placements\Domain\Model\PositionType $type) {
		$this->type = $type;
	}

	/**
	 * __construct
	 *
	 * @return Position
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
		$this->categories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		
		$this->sectors = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

	/**
	 * Adds a Category
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\Category $category
	 * @return void
	 */
	public function addCategory(\TYPO3\CMS\Extbase\Domain\Model\Category $category) {
		$this->categories->attach($category);
	}

	/**
	 * Removes a Category
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\Category $categoryToRemove The Category to be removed
	 * @return void
	 */
	public function removeCategory(\TYPO3\CMS\Extbase\Domain\Model\Category $categoryToRemove) {
		$this->categories->detach($categoryToRemove);
	}

	/**
	 * Returns the categories
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category> $categories
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * Returns a single category
	 * If there are multiple categories only the first one is returned
	 *
	 * @return \TYPO3\CMS\Extbase\Domain\Model\Category
	 */
	public function getSingleCategory() {
		$categories = $this->getCategories()->toArray();
		return (count($categories))? $categories[0]: NULL;
	}

	/**
	 * Set single category
	 * Any category found will be removed beforhand.
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\Category $category
	 * @return void
	 */
	public function setSingleCategory(\TYPO3\CMS\Extbase\Domain\Model\Category $category = NULL) {
		$storage = $this->getCategories();
		if($storage->count()) {
			$storage->removeAll($storage);
		}
		if($category !== NULL) {
			$this->addCategory($category);
		}
	}

	/**
	 * Sets the categories
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category> $categories
	 * @return void
	 */
	public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories) {
		$this->categories = $categories;
	}

	/**
	 * Returns the workingHours
	 *
	 * @return \Webfox\Placements\Domain\Model\WorkingHours workingHours
	 */
	public function getWorkingHours() {
		return $this->workingHours;
	}

	/**
	 * Sets the workingHours
	 *
	 * @param \Webfox\Placements\Domain\Model\WorkingHours $workingHours
	 * @return \Webfox\Placements\Domain\Model\WorkingHours workingHours
	 */
	public function setWorkingHours(\Webfox\Placements\Domain\Model\WorkingHours $workingHours) {
		$this->workingHours = $workingHours;
	}

	/**
	 * Returns the entryDate
	 *
	 * @return \DateTime $entryDate
	 */
	public function getEntryDate() {
		return $this->entryDate;
	}

	/**
	 * Sets the entryDate
	 *
	 * @param \DateTime $entryDate
	 * @return void
	 */
	public function setEntryDate($entryDate) {
		$this->entryDate = $entryDate;
	}

	/**
	 * Returns the fixedTerm
	 *
	 * @return boolean $fixedTerm
	 */
	public function getFixedTerm() {
		return $this->fixedTerm;
	}

	/**
	 * Sets the fixedTerm
	 *
	 * @param boolean $fixedTerm
	 * @return void
	 */
	public function setFixedTerm($fixedTerm) {
		$this->fixedTerm = $fixedTerm;
	}

	/**
	 * Returns the boolean state of fixedTerm
	 *
	 * @return boolean
	 */
	public function isFixedTerm() {
		return $this->getFixedTerm();
	}

	/**
	 * Returns the duration
	 *
	 * @return \string $duration
	 */
	public function getDuration() {
		return $this->duration;
	}

	/**
	 * Sets the duration
	 *
	 * @param \string $duration
	 * @return void
	 */
	public function setDuration($duration) {
		$this->duration = $duration;
	}

	/**
	 * Returns the zip
	 *
	 * @return \string $zip
	 */
	public function getZip() {
		return $this->zip;
	}

	/**
	 * Sets the zip
	 *
	 * @param \string $zip
	 * @return void
	 */
	public function setZip($zip) {
		$this->zip = $zip;
	}

	/**
	 * Returns the city
	 *
	 * @return \string $city
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * Sets the city
	 *
	 * @param \string $city
	 * @return void
	 */
	public function setCity($city) {
		$this->city = $city;
	}

	/**
	 * Gets the latitude
	 *
	 * @return \float $latitude
	 */
	public function getLatitude() {
	    return $this->latitude;
	}

	/**
	 * Sets the latitude
	 *
	 * @var \float $latitude
	 * @return void
	 */
	public function setLatitude($latitude) {
	    $this->latitude = $latitude;
	}

	/**
	 * Gets the longitude
	 *
	 * @return \float $longitude
	 */
	public function getLongitude() {
	    return $this->longitude;
	}

	/**
	 * Sets the longitude
	 *
	 * @var \float $longitude
	 * @return void
	 */
	public function setLongitude($longitude) {
	    $this->longitude = $longitude;
	}

	/**
	 * Returns the payment
	 *
	 * @return \string $payment
	 */
	public function getPayment() {
		return $this->payment;
	}

	/**
	 * Sets the payment
	 *
	 * @param \string $payment
	 * @return void
	 */
	public function setPayment($payment) {
		$this->payment = $payment;
	}

	/**
	 * Returns the contact
	 *
	 * @return \string $contact
	 */
	public function getContact() {
		return $this->contact;
	}

	/**
	 * Sets the contact
	 *
	 * @param \string $contact
	 * @return void
	 */
	public function setContact($contact) {
		$this->contact = $contact;
	}

	/**
	 * Returns the link
	 *
	 * @return \string $link
	 */
	public function getLink() {
		return $this->link;
	}

	/**
	 * Sets the link
	 *
	 * @param \string $link
	 * @return void
	 */
	public function setLink($link) {
		$this->link = $link;
	}

	/**
	 * Adds a Sector
	 *
	 * @param \Webfox\Placements\Domain\Model\Sector $sector
	 * @return void
	 */
	public function addSector(\Webfox\Placements\Domain\Model\Sector $sector) {
		$this->sectors->attach($sector);
	}

	/**
	 * Removes a Sector
	 *
	 * @param \Webfox\Placements\Domain\Model\Sector $sectorToRemove The Sector to be removed
	 * @return void
	 */
	public function removeSector(\Webfox\Placements\Domain\Model\Sector $sectorToRemove) {
		$this->sectors->detach($sectorToRemove);
	}

	/**
	 * Returns the sectors
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Webfox\Placements\Domain\Model\Sector> $sectors
	 */
	public function getSectors() {
		return $this->sectors;
	}

	/**
	 * Sets the sectors
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Webfox\Placements\Domain\Model\Sector> $sectors
	 * @return void
	 */
	public function setSectors(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $sectors) {
		$this->sectors = $sectors;
	}

	/**
	 * Returns the export enabled state
	 *
	 * @return boolean $exportEnabled
	 */
	public function getExportEnabled() {
		return $this->exportEnabled;
	}

	/**
	 * Sets the exportEnabled
	 *
	 * @param boolean $exportEnabled
	 * @return void
	 */
	public function setExportEnabled($exportEnabled) {
		$this->exportEnabled = $exportEnabled;
	}

	/**
	 * Returns the boolean state of exportEnabled
	 *
	 * @return boolean
	 */
	public function isExportEnabled() {
		return $this->getExportEnabled();
	}


}

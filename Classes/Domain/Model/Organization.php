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
class Organization extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * title
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $title;

	/**
	 * Unique external identifier
	 *
	 * @var \string
	 */
	protected $identifier;

	/**
	 * description
	 *
	 * @var \string
	 */
	protected $description;

	/**
	 * Image
	 *
	 * @var \Webfox\Placements\Domain\Model\FileReference
	 */ 
	protected $image;

	/**
	 * sectors
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Webfox\Placements\Domain\Model\Sector>
	 * @lazy
	 */
	protected $sectors;

	/**
	 * Categories
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
	 * @lazy
	 */
	protected $categories;

	/**
	 * Client for whom this position is managed
	 *
	 * @var \Webfox\Placements\Domain\Model\Client
	 * @lazy
	 */
	protected $client;

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
	 * Get the image
	 *
	 * @return \Webfox\Placements\Domain\Model\FileReference
	 */
	public function getImage() {
	    return $this->image;
	}

	/**
	 * Sets the image
	 *
	 * @param \Webfox\Placements\Domain\Model\FileReference $image
	 */
	public function setImage($image) {
		$this->image = $image;
	}

	/**
	 * __construct
	 *
	 * @return Organization
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
	 * Sets the categories
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category> $categories
	 * @return void
	 */
	public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories) {
		$this->categories = $categories;
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

}

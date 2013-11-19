<?php
namespace Webfox\Placements\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Dirk Wenzel <wenzel@webfox01.de>, AgenturWebfox GmbH
 *  Michael Kasten <kasten@webfox01.de>, AgenturWebfox GmbH
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
	 * @var \string
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
	 * @return \string
	 */
	public function getImage() {
	    return $this->image;
	}

	/**
	 * Sets the image
	 *
	 * @param \string $image
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

}
?>

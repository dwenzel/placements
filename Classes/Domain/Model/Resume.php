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
class Resume extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * title
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $title;

	/**
	 * introduction
	 *
	 * @var \string
	 */
	protected $introduction;

	/**
	 * sections
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Webfox\Placements\Domain\Model\Section>
	 * @lazy
	 */
	protected $sections;

	/**
	 * __construct
	 *
	 * @return Resume
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
		$this->sections = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

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
	 * Returns the introduction
	 *
	 * @return \string $introduction
	 */
	public function getIntroduction() {
		return $this->introduction;
	}

	/**
	 * Sets the introduction
	 *
	 * @param \string $introduction
	 * @return void
	 */
	public function setIntroduction($introduction) {
		$this->introduction = $introduction;
	}

	/**
	 * Adds a Section
	 *
	 * @param \Webfox\Placements\Domain\Model\Section $section
	 * @return void
	 */
	public function addSection(\Webfox\Placements\Domain\Model\Section $section) {
		$this->sections->attach($section);
	}

	/**
	 * Removes a Section
	 *
	 * @param \Webfox\Placements\Domain\Model\Section $sectionToRemove The Section to be removed
	 * @return void
	 */
	public function removeSection(\Webfox\Placements\Domain\Model\Section $sectionToRemove) {
		$this->sections->detach($sectionToRemove);
	}

	/**
	 * Returns the sections
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Webfox\Placements\Domain\Model\Section> $sections
	 */
	public function getSections() {
		return $this->sections;
	}

	/**
	 * Sets the sections
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Webfox\Placements\Domain\Model\Section> $sections
	 * @return void
	 */
	public function setSections(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $sections) {
		$this->sections = $sections;
	}

}

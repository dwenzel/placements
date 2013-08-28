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
class Application extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * introduction
	 *
	 * @var \string
	 */
	protected $introduction;

	/**
	 * file
	 *
	 * @var \string
	 */
	protected $file;

	/**
	 * position
	 *
	 * @var \Webfox\Placements\Domain\Model\Position
	 * @lazy
	 */
	protected $position;

	/**
	 * resume
	 *
	 * @var \Webfox\Placements\Domain\Model\Resume
	 */
	protected $resume;

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
	 * Returns the file
	 *
	 * @return \string $file
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * Sets the file
	 *
	 * @param \string $file
	 * @return void
	 */
	public function setFile($file) {
		$this->file = $file;
	}

	/**
	 * Returns the position
	 *
	 * @return \Webfox\Placements\Domain\Model\Position position
	 */
	public function getPosition() {
		return $this->position;
	}

	/**
	 * Sets the position
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $position
	 * @return \Webfox\Placements\Domain\Model\Position position
	 */
	public function setPosition(\Webfox\Placements\Domain\Model\Position $position) {
		$this->position = $position;
	}

	/**
	 * Returns the resume
	 *
	 * @return \Webfox\Placements\Domain\Model\Resume resume
	 */
	public function getResume() {
		return $this->resume;
	}

	/**
	 * Sets the resume
	 *
	 * @param \Webfox\Placements\Domain\Model\Resume $resume
	 * @return \Webfox\Placements\Domain\Model\Resume resume
	 */
	public function setResume(\Webfox\Placements\Domain\Model\Resume $resume) {
		$this->resume = $resume;
	}

}
?>
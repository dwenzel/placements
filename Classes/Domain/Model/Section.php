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
class Section extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * begin
	 *
	 * @var \DateTime
	 */
	protected $begin;

	/**
	 * end
	 *
	 * @var \DateTime
	 */
	protected $end;

	/**
	 * position
	 *
	 * @var \string
	 */
	protected $position;

	/**
	 * description
	 *
	 * @var \string
	 */
	protected $description;

	/**
	 * Returns the begin
	 *
	 * @return \DateTime $begin
	 */
	public function getBegin() {
		return $this->begin;
	}

	/**
	 * Sets the begin
	 *
	 * @param \DateTime $begin
	 * @return void
	 */
	public function setBegin($begin) {
		$this->begin = $begin;
	}

	/**
	 * Returns the end
	 *
	 * @return \DateTime $end
	 */
	public function getEnd() {
		return $this->end;
	}

	/**
	 * Sets the end
	 *
	 * @param \DateTime $end
	 * @return void
	 */
	public function setEnd($end) {
		$this->end = $end;
	}

	/**
	 * Returns the position
	 *
	 * @return \string $position
	 */
	public function getPosition() {
		return $this->position;
	}

	/**
	 * Sets the position
	 *
	 * @param \string $position
	 * @return void
	 */
	public function setPosition($position) {
		$this->position = $position;
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

}

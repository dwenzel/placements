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
?>
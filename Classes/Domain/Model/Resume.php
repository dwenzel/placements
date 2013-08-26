<?php
namespace TYPO3\Placements\Domain\Model;

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
class Resume extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * sections
	 *
	 * @var \TYPO3\Placements\Domain\Model\Section
	 */
	protected $sections;

	/**
	 * Returns the sections
	 *
	 * @return \TYPO3\Placements\Domain\Model\Section $sections
	 */
	public function getSections() {
		return $this->sections;
	}

	/**
	 * Sets the sections
	 *
	 * @param \TYPO3\Placements\Domain\Model\Section $sections
	 * @return void
	 */
	public function setSections(\TYPO3\Placements\Domain\Model\Section $sections) {
		$this->sections = $sections;
	}

}
?>
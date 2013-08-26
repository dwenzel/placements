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
class User extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * person
	 *
	 * @var
	 */
	protected $person;

	/**
	 * resumes
	 *
	 * @var \TYPO3\Placements\Domain\Model\Resume
	 */
	protected $resumes;

	/**
	 * applications
	 *
	 * @var \TYPO3\Placements\Domain\Model\Application
	 */
	protected $applications;

	/**
	 * Returns the resumes
	 *
	 * @return \TYPO3\Placements\Domain\Model\Resume $resumes
	 */
	public function getResumes() {
		return $this->resumes;
	}

	/**
	 * Sets the resumes
	 *
	 * @param \TYPO3\Placements\Domain\Model\Resume $resumes
	 * @return void
	 */
	public function setResumes(\TYPO3\Placements\Domain\Model\Resume $resumes) {
		$this->resumes = $resumes;
	}

	/**
	 * Returns the applications
	 *
	 * @return \TYPO3\Placements\Domain\Model\Application $applications
	 */
	public function getApplications() {
		return $this->applications;
	}

	/**
	 * Sets the applications
	 *
	 * @param \TYPO3\Placements\Domain\Model\Application $applications
	 * @return void
	 */
	public function setApplications(\TYPO3\Placements\Domain\Model\Application $applications) {
		$this->applications = $applications;
	}

	/**
	 * Returns the person
	 *
	 * @return person
	 */
	public function getPerson() {
		return $this->person;
	}

	/**
	 * Sets the person
	 *
	 * @param $person
	 * @return person
	 */
	public function setPerson($person) {
		$this->person = $person;
	}

}
?>
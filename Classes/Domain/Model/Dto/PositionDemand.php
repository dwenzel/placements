<?php
namespace Webfox\Placements\Domain\Model\Dto;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 <wenzel@webfox01.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Position Demand object which holds all information to get the correct
 * Position records.
 *
 * @package placements 
 */
class PositionDemand extends AbstractDemand implements DemandInterface {

	/**
	 * @var \string
	 */
	protected $positionTypes;

	/**
	 * @var \string
	 */
	protected $workingHours;

	/**
	 * @var \string
	 */
	protected $categories;

	/**
	 * @var \string
	 */
	protected $categoryConjunction;

	/**
	 * Get Working Hours
	 * @return \string
	 */
	public function getWorkingHours () {
		return $this->workingHours;
	}

	/**
	 * Sets the Working Hours
	 * @param \string $workingHours A comma separated list of working hours uids
	 */
	public function setWorkingHours ($workingHours) {
		$this->workingHours = $workingHours;
	}

	/**
	 * Get Position Types
	 * @return \string
	 */
	public function getPositionTypes () {
		return $this->positionTypes;
	}

	/**
	 * Sets the Position Types
	 * @param \string $positionTypes A comma separated list of position type uids
	 */
	public function setPositionTypes ($positionTypes) {
		$this->positionTypes = $positionTypes;
	}

	/**
	 * Get Categories
	 * @return \string
	 */
	public function getCategories () {
		return $this->categories;
	}

	/**
	 * Sets the Categories
	 * @param \string $categories A comma separated list of category uids
	 */
	public function setCategories ($categories) {
		$this->categories = $categories;
	}

	/**
	 * Get Category conjunction
	 * @return \string
	 */
	public function getCategoryConjunction () {
		return $this->categoryConjunction;
	}

	/**
	 * Set Category conjunction
	 *
	 * @param \string $conjunction
	 */
	public function setCategoryConjunction ($conjunction) {
		$this->categoryConjunction = $conjunction;
	}
}

?>


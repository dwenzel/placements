<?php
namespace Webfox\Placements\Domain\Model\Dto;
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
 * Organization Demand object which holds all information to get the correct
 * Organization records.
 *
 * @package placements 
 */
class OrganizationDemand extends AbstractDemand implements DemandInterface {

	/**
	* @var \string
	*/
	protected $sectors;

	/**
	 * @var \string
	 */
	protected $constraintsConjunction;

	/**
	 * @var \string
	 */
	protected $categories;

	/**
	 * @var \string
	 */
	protected $clients;

	/**
	 * @var \boolean
	 */
	protected $clientsOrganizationsOnly;

	/**
	 * @var \string
	 */
	protected $categoryConjunction;

	/**
	 * Get Sectors
	 * @return \string
	 */
	public function getSectors () {
	    return $this->sectors;
	}

	/**
	 * Set Sectors
	 * @param \string $sectors A comma separated list of sector uids
	 */
	public function setSectors ($sectors) {
	    $this->sectors = $sectors;
	}

	/**
	 * Get Constraints Conjunction
	 * @return \string
	 */
	public function getConstraintsConjunction () {
		return $this->constraintsConjunction;
	}

	/**
	 * Set Constraints Conjunction
	 *
	 * @param \string $conjunction
	 */
	public function setConstraintsConjunction ($conjunction) {
		$this->constraintsConjunction = $conjunction;
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

	/**
	 * Get Clients
	 * @return \string
	 */
	public function getClients () {
		return $this->clients;
	}

	/**
	 * Sets the Clients
	 * @param \string $clients A comma separated list of client uids
	 */
	public function setClients ($clients) {
		$this->clients = $clients;
	}
	
	/**
	 * Get Clients Organizations Only
	 * @return \boolean
	 */
	public function getClientsOrganizationsOnly() {
		return $this->clientsOrganizationsOnly;
	}

	/** 
	 * Set Clients Organizations Only
	 *
	 * @param \boolean $clientsOrganizationsOnly
	 */
	public function setClientsOrganizationsOnly($clientsOrganizationsOnly) {
		$this->clientsOrganizationsOnly = $clientsOrganizationsOnly;
	}

}

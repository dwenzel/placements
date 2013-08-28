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
class Position extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * organization
	 *
	 * @var \Webfox\Placements\Domain\Model\Organization
	 */
	protected $organization;

	/**
	 * client
	 *
	 * @var \Webfox\Placements\Domain\Model\Client
	 */
	protected $client;

	/**
	 * Returns the organization
	 *
	 * @return \Webfox\Placements\Domain\Model\Organization organization
	 */
	public function getOrganization() {
		return $this->organization;
	}

	/**
	 * Sets the organization
	 *
	 * @param \Webfox\Placements\Domain\Model\Organization $organization
	 * @return \Webfox\Placements\Domain\Model\Organization organization
	 */
	public function setOrganization(\Webfox\Placements\Domain\Model\Organization $organization) {
		$this->organization = $organization;
	}

	/**
	 * Returns the client
	 *
	 * @return \Webfox\Placements\Domain\Model\Client client
	 */
	public function getClient() {
		return $this->client;
	}

	/**
	 * Sets the client
	 *
	 * @param \Webfox\Placements\Domain\Model\Client $client
	 * @return \Webfox\Placements\Domain\Model\Client client
	 */
	public function setClient(\Webfox\Placements\Domain\Model\Client $client) {
		$this->client = $client;
	}

}
?>
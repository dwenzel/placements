<?php
namespace Webfox\Placements\Domain\Repository;
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
class OrganizationRepository extends AbstractDemandedRepository {
	/**
	 * Returns an array of query constraints from a given demand object
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query A query object
	 * @param \Webfox\Placements\Domain\Model\Dto\DemandInterface $demand A demand object
	 * @return \array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint>
	 */
	protected function createConstraintsFromDemand (\TYPO3\CMS\Extbase\Persistence\QueryInterface $query, \Webfox\Placements\Domain\Model\Dto\DemandInterface $demand) {
		//@todo implement constraints
		$constraints = array();
		
		/**
		 * we have to build an empty constraint
		 * in order to prevent organizations without a client from beeing
		 * shown when current user has no client
		 */
		if ($demand->getClients() != '') {
			$clients = explode(',', $demand->getClients());
			foreach($clients as $client) {
				$constraints[] = $query->equals('client.uid', $client);
			}
		} else {
			$constraints[] = $query->equals('client', '');
		}
		return $constraints;
	}

}

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
 * Abstract demanded repository
 * 
 * Implementation based on Georg Ringer's news Extension.
 * @package placements
 *
 * @author Dirk Wenzel <wenzel@webfox01.de>
 */
abstract class AbstractDemandedRepository
	extends \TYPO3\CMS\Extbase\Persistence\Repository {

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbBackend
	 * @inject
	 */
	protected $storageBackend;

	/**
	 * @var \Webfox\Placements\Utility\Geocoder
	 * @inject
	 */
	protected $geoCoder;

	/**
	 * Returns an array of constraints created from a given demand object.
	 * 
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \Webfox\Placements\Domain\Model\Dto\DemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint>
	 * @abstract
	 */
	abstract protected function createConstraintsFromDemand(\TYPO3\CMS\Extbase\Persistence\QueryInterface $query, \Webfox\Placements\Domain\Model\Dto\DemandInterface $demand);

	/**
	 * Returns an array of orderings created from a given demand object.
	 *
	 * @param \Webfox\Placements\Domain\Model\Dto\DemandInterface $demand
	 * @return \array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint>
	 */
	protected function createOrderingsFromDemand(\Webfox\Placements\Domain\Model\Dto\DemandInterface $demand) {
		$orderings = array();

		//@todo validate order (orderAllowed)
		if ($demand->getOrder()) {
			$orderList = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $demand->getOrder(), TRUE);

			if (!empty($orderList)) {
				// go through every order statement
				foreach ($orderList as $orderItem) {
					list($orderField, $ascDesc) = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('|', $orderItem, TRUE);
					// count == 1 means that no direction is given
					if ($ascDesc) {
						$orderings[$orderField] = ((strtolower($ascDesc) == 'desc') ?
							\TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING :
							\TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING);
					} else {
						$orderings[$orderField] = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING;
					}
				}
			}
		}

		return $orderings;
	}

	/**
	 * Returns the objects of this repository matching the demand.
	 *
	 * @param \Webfox\Placements\Domain\Model\Dto\DemandInterface $demand
	 * @param boolean $respectEnableFields
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findDemanded(\Webfox\Placements\Domain\Model\Dto\DemandInterface $demand, $respectEnableFields = TRUE) {
		$query = $this->generateQuery($demand, $respectEnableFields);
		$objects = $query->execute();
		if ($objects->count() AND 
				$demand->getRadius() AND 
				$demand->getGeoLocation()) {
			$objects = $this->filterByRadius(
					$objects,
					$demand->getGeoLocation(),
					$demand->getRadius()/1000
			);
		}
		return $objects;
	}

	/**
	 * Returns a query result filtered by radius around a given location
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $queryResult A query result containing objects
	 * @param \array $geoLocation An array describing a geolocation by lat and lng
	 * @param \integer $distance Distance in meter
	 * @return \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $queryResult A query result containing objects
	 */
	public function filterByRadius($queryResult, $geoLocation, $distance) {
		$objectUids = array();
		foreach($queryResult->toArray() as $object) {
			$currDist = $this->geoCoder->distance(
				$geoLocation['lat'], 
				$geoLocation['lng'],
				$object->getLatitude(),
				$object->getLongitude()
			);
			if ($currDist <= $distance) {
				$objectUids[] = $object->getUid();
			}
		}
		$orderings = $queryResult->getQuery()->getOrderings();
		$sortField = array_shift(array_keys($orderings));
		$sortOrder = array_shift(array_values($orderings));
		$objects = $this->findMultipleByUid(
			implode(',', $objectUids), $sortField, $sortOrder
		);
		return $objects;
	}

	/**
	 * Returns multiple records by uid sorted by a given field and order.
	 *
	 * @param \string $recordList A comma separated list of uids
	 * @param \string $sortField Field to sort by
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $sortOrder 
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResult
	 */
	public function findMultipleByUid ($recordList, $sortField = 'uid', $sortOrder = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING) {
		$uids = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $recordList, TRUE);
		$query = $this->createQuery();
		$query->matching($query->in('uid' , $uids));
		$query->setOrderings(array($sortField => $sortOrder));
		return $query->execute();
	}

	protected function generateQuery(\Webfox\Placements\Domain\Model\Dto\DemandInterface $demand, $respectEnableFields = TRUE) {
		$query = $this->createQuery();
		$constraints = $this->createConstraintsFromDemand($query, $demand);

		/**
		 * We have to get the storage pages here and set the constraint by pid
		 * in order to avoid records from wrong storage pages from being displayed. 
		 * For some reasons the persistence manager does not respect the correct
		 * storage page settings in our ajaxListAction from PositionController (but it
		 * does for the plugin settings).
		 */
		$constraints[] = $query->in('pid', $query->getQuerySettings()->getStoragePageIds());

		if ($respectEnableFields === FALSE) {
			$query->getQuerySettings()->setRespectEnableFields(FALSE);
		} else {
			/**
			 * @todo  we set deleted and hidden to 0 here 
			 * because otherwise our ajax action
			 * will return those records too! 
			 * (Seems the enable fields are not
			 * respected properly.)
			 */
			$constraints[] = $query->equals('deleted', 0);
			$constraints[] = $query->equals('hidden', 0);
		}

		if (!empty($constraints)) {
			$query->matching(
				$query->logicalAnd($constraints)
			);
		}

		if ($orderings = $this->createOrderingsFromDemand($demand)) {
			$query->setOrderings($orderings);
		}

		if ($demand->getLimit() != NULL) {
			$query->setLimit((int) $demand->getLimit());
		}

		if ($demand->getOffset() != NULL) {
			$query->setOffset((int) $demand->getOffset());
		}

		return $query;
	}

	/**
	 * Returns the total number objects of this repository matching the demand.
	 *
	 * @param \Webfox\Placements\Domain\Model\Dto\DemandInterface $demand
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function countDemanded(\Webfox\Placements\Domain\Model\Dto\DemandInterface $demand) {
		$result = $this->findDemanded($demand);
		return $result->count();
	}
}

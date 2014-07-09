<?php
namespace Webfox\Placements\Domain\Repository;

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
class PositionRepository extends AbstractDemandedRepository {
	
	/**
	 * Returns an array of query constraints from a given demand object
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query A query object
	 * @param \Webfox\Placements\Domain\Model\Dto\DemandInterface $demand A demand object
	 * @return \array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint>
	 */
	protected function createConstraintsFromDemand (\TYPO3\CMS\Extbase\Persistence\QueryInterface $query, \Webfox\Placements\Domain\Model\Dto\DemandInterface $demand) {
		$constraints = array();
		$categories = $demand->getCategories();
		$categoryConjunction = $demand->getCategoryConjunction();
	
		// Category constraints
		if ($categories && !empty($categoryConjunction)) {
			
			// @todo get subcategories ($demand->getIncludeSubCategories())
			$constraints[] = $this->createCategoryConstraint(
				$query,
				$categories,
				$categoryConjunction,
				FALSE
			);
		}
		$constraintsConjunction = $demand->getConstraintsConjunction();
		// Position type constraints
		if ($demand->getPositionTypes()) {
			$positionTypes = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $demand->getPositionTypes());
			$positionConstraints = array();
			foreach ($positionTypes as $positionType) {
				$positionConstraints[] = $query->equals('type.uid', $positionType);
			}
			if (count($positionConstraints)) {
				switch ($constraintsConjunction) {
					case 'or':
						$constraints[] = $query->logicalOr($positionConstraints);
						break;
					case 'and':
					default:
						$constraints[] = $query->logicalAnd($positionConstraints);
				}
			}
		}

		// Working hours constraints
		if ($demand->getWorkingHours()) {
			$workingHours = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $demand->getWorkingHours());
			$wHoursConstraints = array();
			foreach ($workingHours as $workingHour) {
				$wHoursConstraints[] = $query->equals('workingHours.uid', $workingHour);
			}
			if (count($wHoursConstraints)) {
				switch ($constraintsConjunction) {
					case 'or':
						$constraints[] = $query->logicalOr($wHoursConstraints);
						break;
					case 'and':
					default:
						$constraints[] = $query->logicalAnd($wHoursConstraints);
				}
			}
		}

		// Sector constraints
		if ($demand->getSectors()) {
			$sectors = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $demand->getSectors());
			$sectorsConstraints = array();
			foreach ($sectors as $sector) {
				$sectorsConstraints[] = $query->equals('sectors.uid', $sector);
			}
			if (count($sectorsConstraints)) {
				switch ($constraintsConjunction) {
					case 'or':
						$constraints[] = $query->logicalOr($sectorsConstraints);
						break;
					case 'and':
					default:
						$constraints[] = $query->logicalAnd($sectorsConstraints);
				}
			}
		}

		// Search constraints
		if ($demand->getSearch()) {
			$searchConstraints = array();
			$locationConstraints = array();
			$search = $demand->getSearch();
			$subject = $search->getSubject();

			if(!empty($subject)) {
				// search text in specified search fields
				$searchFields = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $search->getFields(), TRUE);
				if (count($searchFields) === 0) {
					throw new \UnexpectedValueException('No search fields given', 1382608407);
				}
				foreach($searchFields as $field) {
					$searchConstraints[] = $query->like($field, '%' . $subject . '%');
				}
			}

			// search by bounding box
			$bounds = $search->getBounds();
			$location = $search->getLocation();
			$radius = $search->getRadius();

			if(!empty($location)
					AND !empty($radius)
					AND empty($bounds)) {
					$geoCoder = new \Webfox\Placements\Utility\Geocoder;
					$geoLocation = $geoCoder::getLocation($location);
					if ($geoLocation) {
						$bounds = $geoCoder::getBoundsByRadius($geoLocation['lat'], $geoLocation['lng'], $radius/1000);
					}
			}
			if($bounds AND
					!empty($bounds['N']) AND
					!empty($bounds['S']) AND
					!empty($bounds['W']) AND
					!empty($bounds['E'])) {
						$locationConstraints[] = $query->greaterThan('latitude', $bounds['S']['lat']);
						$locationConstraints[] = $query->lessThan('latitude', $bounds['N']['lat']);
						$locationConstraints[] = $query->greaterThan('longitude', $bounds['W']['lng']);
						$locationConstraints[] = $query->lessThan('longitude', $bounds['E']['lng']);
			}
					
			if(count($searchConstraints)) {
				$constraints[] = $query->logicalOr($searchConstraints);
			}
			if(count($locationConstraints)) {
				$constraints[] = $query->logicalAnd($locationConstraints);
			}
		}

		// Clients constraints
		if ($demand->getClients()) {
				$clientConstraints = array();
				$clients = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',',$demand->getClients());
				foreach($clients as $client) {
					$clientConstraints[] = $query->equals('client.uid', $client);
				}
				if(count($clientConstraints)) {
					$constraints[] = $query->logicalOr($clientConstraints);
				}
		}

				

		return $constraints;
	}


	/**
	 * Returns a category constraint created by
	 * a given list of categories and a junction string
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param  \array $categories
	 * @param  \string $conjunction
	 * @param  \boolean $includeSubCategories
	 * @return \TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint|null
	 */
	protected function createCategoryConstraint(\TYPO3\CMS\Extbase\Persistence\QueryInterface $query, $categories, $conjunction, $includeSubCategories = FALSE) {
		$constraint = NULL;
		$categoryConstraints = array();

		// If "ignore category selection" is used, nothing needs to be done
		if (empty($conjunction)) {
			return $constraint;
		}

		if (!is_array($categories)) {
			$categories = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $categories, TRUE);
		}
		foreach ($categories as $category) {
			if ($includeSubCategories) {
				/*
				$subCategories = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', Tx_News_Service_CategoryService::getChildrenCategories($category, 0, '', TRUE), TRUE);
				$subCategoryConstraint = array();
				$subCategoryConstraint[] = $query->contains('categories', $category);
				if (count($subCategories) > 0) {
					foreach ($subCategories as $subCategory) {
						$subCategoryConstraint[] = $query->contains('categories', $subCategory);
					}
				}
				if ($subCategoryConstraint) {
					$categoryConstraints[] = $query->logicalOr($subCategoryConstraint);
				}
*/
			} else {
				$categoryConstraints[] = $query->contains('categories', $category);
			}
		}
		if ($categoryConstraints) {
			switch (strtolower($conjunction)) {
				case 'or':
					$constraint = $query->logicalOr($categoryConstraints);
					break;
				case 'notor':
					$constraint = $query->logicalNot($query->logicalOr($categoryConstraints));
					break;
				case 'notand':
					$constraint = $query->logicalNot($query->logicalAnd($categoryConstraints));
					break;
				case 'and':
				default:
					$constraint = $query->logicalAnd($categoryConstraints);
			}
		}

		return $constraint;
	}

}
?>

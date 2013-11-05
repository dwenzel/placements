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
		
		if ($demand->getCategories() && $demand->getCategories() !== '0') {
			
			// @todo get subcategories ($demand->getIncludeSubCategories())
			$constraints[] = $this->createCategoryConstraint(
				$query,
				$demand->getCategories(),
				$demand->getCategoryConjunction(),
				FALSE
			);
		}

		//@todo add constraints
		return $constraints;
	}

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

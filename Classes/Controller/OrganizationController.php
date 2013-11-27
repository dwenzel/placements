<?php
namespace Webfox\Placements\Controller;

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
class OrganizationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * Organization Repository
	 *
	 * @var \Webfox\Placements\Domain\Repository\OrganizationRepository
	 * @inject
	 */
	protected $organizationRepository;

	/**
	 * Sector Repository
	 *
	 * @var \Webfox\Placements\Domain\Repository\SectorRepository
	 * @inject
	 */
	protected $sectorRepository;

	/**
	 * Category Repository
	 *
	 * @var \Webfox\Placements\Domain\Repository\CategoryRepository
	 * @inject
	 */
	protected $categoryRepository;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$organizations = $this->organizationRepository->findAll();
		$this->view->assign('organizations', $organizations);
	}

	/**
	 * action show
	 *
	 * @param \Webfox\Placements\Domain\Model\Organization $organization
	 * @return void
	 */
	public function showAction(\Webfox\Placements\Domain\Model\Organization $organization) {
		$this->view->assign('organization', $organization);
	}

	/**
	 * action new
	 *
	 * @param \Webfox\Placements\Domain\Model\Organization $newOrganization
	 * @ignorevalidation $newOrganization
	 * @return void
	 */
	public function newAction(\Webfox\Placements\Domain\Model\Organization $newOrganization = NULL) {
		$sectors = $this->sectorRepository->findMultipleByUid($this->settings['sectors'], 'title');
		$categories = $this->categoryRepository->findMultipleByUid($this->settings['categories'], 'title');
		$this->view->assignMultiple(array(
			'newOrganization' => $newOrganization,
			'sectors' => $sectors,
			'categories' => $categories,
		));
	}

	/**
	 * action create
	 *
	 * @param \Webfox\Placements\Domain\Model\Organization $newOrganization
	 * @return void
	 */
	public function createAction(\Webfox\Placements\Domain\Model\Organization $newOrganization) {
		$this->organizationRepository->add($newOrganization);
		$this->flashMessageContainer->add(
			\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
				'tx_placements.success.organization.createAction', 'placements'
			)
		);
		$this->redirect('list');
	}

	/**
	 * action edit
	 *
	 * @param \Webfox\Placements\Domain\Model\Organization $organization
	 * @ignorevalidation $organization
	 * @return void
	 */
	public function editAction(\Webfox\Placements\Domain\Model\Organization $organization) {
		$sectors = $this->sectorRepository->findMultipleByUid($this->settings['sectors'], 'title');
		$categories = $this->categoryRepository->findMultipleByUid($this->settings['categories'], 'title');
		$this->view->assignMultiple(array(
			'organization'=> $organization,
			'sectors' => $sectors,
			'categories' => $categories,
		));
	}

	/**
	 * action update
	 *
	 * @param \Webfox\Placements\Domain\Model\Organization $organization
	 * @return void
	 */
	public function updateAction(\Webfox\Placements\Domain\Model\Organization $organization) {
		$this->updateStorageProperties($organization);
		$this->organizationRepository->update($organization);
		$this->flashMessageContainer->add(
			\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
				'tx_placements.success.organization.updateAction', 'placements'
			)	
		);
		$this->redirect('list');
	}

	/**
	 * action delete
	 *
	 * @param \Webfox\Placements\Domain\Model\Organization $organization
	 * @return void
	 */
	public function deleteAction(\Webfox\Placements\Domain\Model\Organization $organization) {
		$this->organizationRepository->remove($organization);
		$this->flashMessageContainer->add(
			\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
				'tx_placements.success.organizationl.deleteAction', 'placements'
			)
		);
		$this->redirect('list');
	}

	/**
	 * Update storage properties
	 *
	 * @param \Webfox\Placements\Domain\Model\Organization $organization
	 */
	 protected function updateStorageProperties(\Webfox\Placements\Domain\Model\Organization &$organization) {
		$args = $this->request->getArgument('organization');
		// get sectors
		if (is_array($args['sectors'])) {
			$choosenSectors = $args['sectors'];
		} else {
			$choosenSectors = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $args['sectors']);
		}
		foreach ($choosenSectors as $choosenSector) {
			$sector = $this->sectorRepository->findOneByUid($choosenSector);
			$sectors = $organization->getSectors();
			if (!$sectors->contains($sector)) {
				$sectors->attach($sector);
				$organization->setSectors($sectors);
			}
		}

		// get categories
		if (is_array($args['categories'])) {
			$choosenCategories = $args['categories'];
		} else {
			$choosenCategories = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $args['categories']);
		}
		foreach ($choosenCategories as $choosenCategory) {
			$category = $this->categoryRepository->findOneByUid($choosenCategory);
			$categories = $organization->getCategories();
			if(!$categories->contains($category)) {
				$categories->attach(category);
				$organization->setCategories($categories);
			}
		}
	 }

	/**
	 * A template method for displaying custom error flash messages, or to
	 * display no flash message at all on errors.
	 *
	 * @return string|boolean The flash message or FALSE if no flash message should be set
	 * @override \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
	 */
	 protected function getErrorFlashMessage() {
		return \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
	 	'tx_placements.error'.'.organization.'. $this->actionMethodName, 'placements');
	 }

}
?>

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
class OrganizationController extends AbstractController {

	/**
	 * Initialize all actions
	 */
	public function initializeAction() {
		if($this->arguments->hasArgument('newOrganization')) {
			$this->arguments->getArgument('newOrganization')
			->getPropertyMappingConfiguration()
			->setTargetTypeForSubProperty('image', 'array');
		}
		if($this->arguments->hasArgument('organization')) {
			$this->arguments->getArgument('organization')
			->getPropertyMappingConfiguration()
			->setTargetTypeForSubProperty('image', 'array');
		}
	}
	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$demand = $this->createDemandFromSettings($this->settings);
		$variables = array (
			'organizations' => $this->organizationRepository->findDemanded($demand),
			'settings' => $this->settings
		);
		$this->view->assignMultiple($variables);
	}

	/**
	 * action show
	 *
	 * @param \Webfox\Placements\Domain\Model\Organization $organization
	 * @return void
	 */
	public function showAction(\Webfox\Placements\Domain\Model\Organization $organization) {
		$this->view->assignMultiple(
				array(
					'organization' => $organization,
					'settings' => $this->settings
				));
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
			'settings' => $this->settings
		));
	}

	/**
	 * action create
	 *
	 * @param \Webfox\Placements\Domain\Model\Organization $newOrganization
	 * @return void
	 */
	public function createAction(\Webfox\Placements\Domain\Model\Organization $newOrganization) {
		$this->updateFileProperty($newOrganization, 'image');
		$newOrganization->setClient($this->accessControlService->getFrontendUser()->getClient());
		$this->organizationRepository->add($newOrganization);
		$this->addFlashMessage(
			$this->translate('tx_placements.success.organization.createAction')
		);
		$redirectParams = array('list');
	if($this->request->hasArgument('save-reload') OR 
			$this->request->hasArgument('save-view' )) {
			$this->persistenceManager->persistAll();
		}
		if ($this->request->hasArgument('save-reload')) {
			$redirectParams = array('edit', NULL, NULL, array('organization' => $newOrganization));
		} elseif ($this->request->hasArgument('save-view')) {
			$redirectParams = array('show', NULL, NULL, array('organization' => $newOrganization));
		}
		call_user_func_array(array($this, 'redirect'), $redirectParams);
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
			'settings' => $this->settings
		));
	}

	/**
	 * action update
	 *
	 * @param \Webfox\Placements\Domain\Model\Organization $organization
	 * @return void
	 */
	public function updateAction(\Webfox\Placements\Domain\Model\Organization $organization) {
		$this->updateFileProperty($organization, 'image');
		$this->organizationRepository->update($organization);
		$this->addFlashMessage(
			$this->translate('tx_placements.success.organization.updateAction')	
		);
		$redirectParams = array('list');
		if($this->request->hasArgument('save-view')) {
			$redirectParams = array('show', NULL, NULL, array('organization' => $organization));
		} elseif ($this->request->hasArgument('save-reload')) {
			$redirectParams = array('edit', NULL, NULL, array('organization' => $organization));
		}
		call_user_func_array(array($this, 'redirect'), $redirectParams);
	}

	/**
	 * action delete
	 *
	 * @param \Webfox\Placements\Domain\Model\Organization $organization
	 * @return void
	 */
	public function deleteAction(\Webfox\Placements\Domain\Model\Organization $organization) {
		$this->organizationRepository->remove($organization);
		$this->addFlashMessage(
			$this->translate('tx_placements.success.organization.deleteAction')
		);
		$this->redirect('list');
	}

	/**
	 * A template method for displaying custom error flash messages, or to
	 * display no flash message at all on errors.
	 *
	 * @return string|boolean The flash message or FALSE if no flash message should be set
	 * @override \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
	 */
	 protected function getErrorFlashMessage() {
		return $this->translate('tx_placements.error'.'.organization.'. $this->actionMethodName);
	 }

	 /**
	 	* Create demand from settings
	 	*
	 	* @param \array $settings
	 	* @return \Webfox\Placements\Domain\Model\Dto\OrganizationDemand
	 	*/
	 public function createDemandFromSettings($settings) {
		$demand = $this->objectManager->get('Webfox\\Placements\\Domain\\Model\\Dto\\OrganizationDemand');
		$settableProperties = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getSettablePropertyNames($demand);
		foreach($settableProperties as $property) {
			if (isset($settings[$property])) {
				\TYPO3\CMS\Extbase\Reflection\ObjectAccess::setProperty(
					$demand,
					$property,
					$settings[$property]);
			}
		}
		if(isset($settings['clientsOrganizationsOnly'])) {
			// we set clientOrganizationsOnly directly since Reflection ObjectAccess seem to miss boolean values (TRUE is cast to 1?)
			$demand->setClientsOrganizationsOnly($settings['clientsOrganizationsOnly']);
			if($this->accessControlService->hasLoggedInClient()) {
				$clientId = $this->accessControlService->getFrontendUser()
										->getClient()->getUid();
				$demand->setClients((string)$clientId);
			} else {
				$demand->setClients('');
			}
		}
		// @todo implement OrderDemand to get rid of this string juggling
		if((isset($settings['orderBy'])) AND (isset($settings['orderDirection']))) {
			$demand->setOrder($settings['orderBy'] . '|' . $settings['orderDirection']);
		}
		return $demand;
	}
}
?>

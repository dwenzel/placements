<?php
namespace Webfox\Placements\Controller;
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
use Webfox\Placements\Property\TypeConverter\ResourceConverter;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration;

class OrganizationController extends AbstractController {

	/**
	 * Set TypeConverter option for image upload
	 */
	public function initializeCreateAction() {
		$this->setTypeConverterConfigurationForImageUpload('newOrganization');
	}

	/**
	 * Set TypeConverter option for image upload
	 */
	public function initializeUpdateAction() {
		$this->setTypeConverterConfigurationForImageUpload('organization');
		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->arguments);
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
		$newOrganization->setClient($this->accessControlService->getFrontendUser()->getClient());
		$this->organizationRepository->add($newOrganization);
		$this->addFlashMessage(
			$this->translate('tx_placements.success.organization.createAction')
		);
		$redirectParams = array('list');
		if($this->request->hasArgument('save-reload') OR 
				$this->request->hasArgument('save-view' )) {
			$persistenceManager = $this->objectManager->get('TYPO3\\CMS\\Extbase\Persistence\\Generic\\PersistenceManager');
			$persistenceManager->persistAll();
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
		if($this->accessControlService->isAllowedToDelete('organization')) {
			$referenceCount = $this->positionRepository->countByOrganization($organization);
			if(!$referenceCount) {
				$this->organizationRepository->remove($organization);
				$messageKey = 'tx_placements.success.organization.deleteAction';
			} else {
				$messageKey = 'tx_placements.error.organization.canNotDeleteOrganizationReferencedByPositions';
			}
		} else {
			$messageKey = 'tx_placements.error.organization.deleteActionNotAllowed';
		}
		$this->addFlashMessage($this->translate($messageKey));
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

	/**
	 * Sets the type converter configuration for an uploaded image for a given argument
	 */
	protected function setTypeConverterConfigurationForImageUpload($argumentName) {
		$uploadConfiguration = array(
			ResourceConverter::CONFIGURATION_ALLOWED_FILE_EXTENSIONS => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
			ResourceConverter::CONFIGURATION_UPLOAD_FOLDER => '1:/content/',
		);
		/** @var PropertyMappingConfiguration $mappingConfiguration */
		$mappingConfiguration = $this->arguments[$argumentName]->getPropertyMappingConfiguration();
		/**
		 * @FIXME mapping fails with Invalid Argument Exception 
		 * (code: 1300098528, incorrect reference to original file given for FileReference) in sysext/core/Classes/Resource/FileReference.php
		 */  
		$mappingConfiguration->forProperty('image')
			->setTypeConverterOptions(
				'Webfox\\Placements\\Property\\TypeConverter\\ResourceConverter',
				$uploadConfiguration
			)
			->skipProperties('name', 'type', 'tmp_name', 'error', 'size');
	}
}


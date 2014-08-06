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
class PositionController extends AbstractController {

	/**
	 * Position Type Repository
	 * 
	 * @var \Webfox\Placements\Domain\Repository\PositionTypeRepository
	 * @inject
	 */
	protected $positionTypeRepository;

	/**
	 * Working Hours Repository
	 *
	 * @var \Webfox\Placements\Domain\Repository\WorkingHoursRepository
	 * @inject
	 */
	protected $workingHoursRepository;

	/**
	 * Initialize Action
	 *
	 */
	 public function initializeAction() {
		$this->setRequestArguments();
		$this->setReferrerArguments();
		$this->organizationRepository->setDefaultOrderings(array('title' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
		if($positionEntryDateConf = $this->getMappingConfigurationForProperty('position', 'entryDate')) {
			$positionEntryDateConf->setTypeConverterOption(
				'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter', 
				\TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 
				$this->settings['position']['edit']['entryDate']['format']
			);
		}
		if($positionSectorsConf = $this->getMappingConfigurationForProperty('position', 'sectors')) {
			$positionSectorsConf->allowProperties('__identity');
		}
		if($positionCategoriesConf = $this->getMappingConfigurationForProperty('position', 'categories')) {
			$positionCategoriesConf->allowProperties('__identity');
		}
		if($positionEntryDateConf = $this->getMappingConfigurationForProperty('newPosition', 'entryDate')) {
			$positionEntryDateConf->setTypeConverterOption(
				'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter', 
				\TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 
				$this->settings['position']['create']['entryDate']['format']
			);
		}
	}

	/**
	 * Initialize ajax list action
	 */
	 public function initializeAjaxListAction() {
	 	if($this->arguments->hasArgument('overwriteDemand')) {
	 		$this->arguments->getArgument('overwriteDemand')
	 		->getPropertyMappingConfiguration()
	 		//->forProperty('overwriteDemand')
	 		->setTypeConverter(
	 			$this->objectManager->get('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\ArrayConverter')
	 			);
	 	}
	 }

	/**
	 * Initialize ajax show action
	 */
	 public function initializeAjaxShowAction() {
	 	if($this->arguments->hasArgument('uid')) {
	 		$this->arguments->getArgument('uid')
	 		->getPropertyMappingConfiguration()
	 		->setTypeConverter(
	 			$this->objectManager->get('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\StringConverter')
	 			);
	 	}
	 }

	/**
	 * action list
	 *
	 * @param \array $overwriteDemand Demand overwriting the current settings. Optional.
	 * @return void
	 */
	public function listAction($overwriteDemand = NULL) {
		$demand = $this->createDemandFromSettings($this->settings);
		if($overwriteDemand) {
		    $demand = $this->overwriteDemandObject($demand, $overwriteDemand);
		}
		
		$positions = $this->positionRepository->findDemanded($demand);	
		if(!$positions->count()) {
			$this->addFlashMessage(
					$this->translate('tx_placements.list.position.message.noPositionFound')
			);
		}
		$this->view->assignMultiple(
			array(
				'positions'=> $positions,
				'overwriteDemand' => $overwriteDemand,
				'requestArguments' => $this->requestArguments
			)
		);
	}

	/**
	 * action ajaxList
	 *
	 * @param \array $overwriteDemand Demand overwriting the current settings. Optional.
	 * @return \string
	 */
	public function ajaxListAction($overwriteDemand = NULL) {
		$demand = $this->createDemandFromSettings($this->settings);
		if($overwriteDemand) {
		    $demand = $this->overwriteDemandObject($demand, $overwriteDemand);
		}
		$positions = $this->positionRepository->findDemanded($demand, TRUE)->toArray();
		$result = array();
		foreach($positions as $position) {
			$type = $position->getType();
			if ($type) {
				$typeArray = array(
					'uid' => $type->getUid(),
					'title' => $type->getTitle(),
				);
			}
			$result[] = array(
					'uid' => $position->getUid(),
					'title' => $position->getTitle(),
					'summary' => $position->getSummary(),
					'city' => $position->getCity(),
					'zip' => $position->getZip(),
					'latitude' => $position->getLatitude(),
					'longitude' => $position->getLongitude(),
					'type' => ($typeArray)? $typeArray: NULL,
					);
		}
		return json_encode($result);
	}

	/**
	 * action count
	 *
	 * @param \array $overwriteDemand Demand overwriting the current settings. Optional.
	 * @return void
	 */
	public function countAction($overwriteDemand = NULL) {
		$demand = $this->createDemandFromSettings($this->settings);
		if($overwriteDemand) {
		    $demand = $this->overwriteDemandObject($demand, $overwriteDemand);
		}

	
		if (!empty($overwriteDemand['search'])) {
			//@todo: throw exception if search fields are not set
			$searchObject = $this->createSearchObject($overwriteDemand['search'], $this->settings['position']['search']);
			$demand->setSearch($searchObject);
		}
		$count = $this->positionRepository->countDemanded($demand);	
		$this->view->assignMultiple(
				array(
					'count'=> $count,
					'demand' => $demand,
					'requestArguments' => $this->requestArguments
			)
		);
	}

	/**
	 * action ajax show
	 *
	 * @param \string $uid Uid of postion to show
	 * @return \string
	 */
	public function ajaxShowAction($uid) {
		$position = $this->positionRepository->findByUid($uid);
		if ($position) {
				$type = $position->getType();
				if ($type) {
					$typeArray = array(
						'uid' => $type->getUid(),
						'title' => $type->getTitle(),
					);
				}
				$result[] = array(
						'uid' => $position->getUid(),
						'title' => $position->getTitle(),
						'summary' => $position->getSummary(),
						'city' => $position->getCity(),
						'zip' => $position->getZip(),
						'latitude' => $position->getLatitude(),
						'longitude' => $position->getLongitude(),
						'type' => ($typeArray)? $typeArray: NULL,
						);
			return json_encode($result);
		}
	}

	/**
	 * action show
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $position
	 * @return void
	 */
	public function showAction(\Webfox\Placements\Domain\Model\Position $position) {
		$this->view->assignMultiple(
			array(
				'position' => $position,
				'referrerArguments' => $this->referrerArguments
			)
		);
	}

	/**
	 * action new
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $newPosition
	 * @ignorevalidation $newPosition
	 * @return void
	 */
	public function newAction(\Webfox\Placements\Domain\Model\Position $newPosition = NULL) {
		if(!$this->accessControlService->isAllowedToCreate('position')) {
			$this->addFlashMessage(
				$this->translate('tx_placements.error.position.createActionNotAllowed')
			);
			$this->redirect('list', NULL, NULL, NULL, $this->settings['listPid']);
		} else {
			$positionTypes = $this->positionTypeRepository->findMultipleByUid($this->settings['positionTypes'], 'title');
			$workingHours = $this->workingHoursRepository->findMultipleByUid($this->settings['workingHours'], 'title');
			$categories = $this->categoryRepository->findMultipleByUid($this->settings['categories'], 'title');
			$sectors = $this->sectorRepository->findMultipleByUid($this->settings['sectors'], 'title');
			$user = $this->accessControlService->getFrontendUser();
			if ($user AND $user->getClient()) {
			    $organizations = $this->organizationRepository->findByClient($user->getClient());
			} else {
				$organizations = $this->organizationRepository->findAll();
			}
			$this->view->assignMultiple(array(
				'newPosition' => $newPosition,
				'workingHours' => $workingHours,
				'positionTypes' => $positionTypes,
				'categories' => $categories,
				'sectors' => $sectors,
				'organizations' => $organizations,
			));
		}
	}

	/**
	 * action create
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $newPosition
	 * @return void
	 */
	public function createAction(\Webfox\Placements\Domain\Model\Position $newPosition) {
		$newPosition->setClient($this->accessControlService->getFrontendUser()->getClient());
		$argument = $this->request->getArgument('newPosition');
		if(isset($argument['categories'])) {
			if(is_array($argument['categories'])) {
				$categories = $this->categoryRepository->findMultipleByUid(implode(',', $argument['categories']));
				foreach($categories as $category) {
					$newPosition->addCategory($category);
				}
			} else {
				$category = $this->categoryRepository->findByUid(intval($argument['categories']));
				$newPosition->setSingleCategory($category);
			}
		}
		$this->geoCoder->updateGeoLocation($newPosition);
		$this->positionRepository->add($newPosition);
		$this->addFlashMessage(
			$this->translate('tx_placements.success.position.createAction')
			);
		$this->forward('show', NULL, NULL, array('position'=>$newPosition), $this->settings['detailPid']);
	}

	/**
	 * action edit
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $position
	 * @ignorevalidation $position
	 * @return void
	 */
	public function editAction(\Webfox\Placements\Domain\Model\Position $position) {
		if(!$this->accessControlService->isAllowedToEdit('position')) {
			$this->addFlashMessage(
				$this->translate('tx_placements.error.position.editActionNotAllowed')
			);
			$this->redirect('list', NULL, NULL, NULL, $this->settings['listPid']);
		} else {
			$positionTypes = $this->positionTypeRepository->findMultipleByUid($this->settings['positionTypes'], 'title');
			$workingHours = $this->workingHoursRepository->findMultipleByUid($this->settings['workingHours'], 'title');
			$categories = $this->categoryRepository->findMultipleByUid($this->settings['categories'], 'title');
			$sectors = $this->sectorRepository->findMultipleByUid($this->settings['sectors'], 'title');
			$user = $this->accessControlService->getFrontendUser();
			if ($user AND $user->getClient()) {
			    $organizations = $this->organizationRepository->findByClient($user->getClient());
			} else {
				$organizations = $this->organizationRepository->findAll();
			}
			$this->view->assignMultiple(array(
				'position' => $position,
				'workingHours' => $workingHours,
				'positionTypes' => $positionTypes,
				'categories' => $categories,
				'sectors' => $sectors,
				'organizations' => $organizations,
			));
		}
	}

	/**
	 * action update
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $position
	 * @return void
	 */
	public function updateAction(\Webfox\Placements\Domain\Model\Position $position) {
		$argument = $this->request->getArgument('position');
		if(isset($argument['categories'])) {
			if(is_array($argument['categories'])) {
				$categories = $this->categoryRepository->findMultipleByUid(implode(',', $argument['categories']));
				$storage = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\ObjectStorage');
				$position->setCategories($storage);
				foreach($categories as $category) {
					$position->addCategory($category);
				}
			} else {
				$category = $this->categoryRepository->findByUid(intval($argument['categories']));
				$position->setSingleCategory($category);
			}
		}
		$this->geoCoder->updateGeoLocation($position);
		$this->positionRepository->update($position);
		$this->addFlashMessage(
			$this->translate('tx_placements.success.position.updateAction')
		);
		$this->forward('show', NULL, NULL, array('position' => $position), $this->settings['detailPid']);
	}

	/**
	 * action delete
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $position
	 * @return void
	 */
	public function deleteAction(\Webfox\Placements\Domain\Model\Position $position) {
		if($this->accessControlService->isAllowedToDelete('position')) {
			$this->positionRepository->remove($position);
			$this->addFlashMessage(
				$this->translate('tx_placements.success.position.deleteAction')
			);
		} else {
			$this->addFlashMessage(
				$this->translate('tx_placements.error.position.deleteActionNotAllowed')
			);
		}
		$this->redirect('list', NULL, NULL, NULL, $this->settings['listPid']);
	}

	/**
	 * Quick Menu action
	 *
	 * @param \array $overwriteDemand Demand overwriting the current settings. Optional.
	 * @return void
	 */
	public function quickMenuAction(array $overwriteDemand = NULL) {
		$positionTypes = $this->positionTypeRepository->findMultipleByUid($this->settings['positionTypes'], 'title');
		$workingHours = $this->workingHoursRepository->findMultipleByUid($this->settings['workingHours'], 'title');
		$sectors = $this->sectorRepository->findMultipleByUid($this->settings['sectors'], 'title');
		$categories = $this->categoryRepository->findMultipleByUid($this->settings['categories'], 'title');
		$this->view->assignMultiple(
				array(
					'positionTypes' => $positionTypes,
					'workingHours' => $workingHours,
					'sectors' => $sectors,
					'categories' => $categories,
					'overwriteDemand' => $overwriteDemand,
					'search' => $search
				)
		);
	}

	/**
	 * Displays a Simple Search Form
	 *
	 * @param \array $search
	 * @return void
	 */
	public function searchFormAction($search = NULL) {
		$this->view->assign('search', $search);
	}

	/**
	 * Displays the Extended Search Form
	 *
	 * @param \array $search
	 * @param \array $overwriteDemand Demand overwriting the current settings. Optional.
	 * @return void
	 */
	public function extendedSearchFormAction($search = NULL, array $overwriteDemand = NULL) {
		$positionTypes = $this->positionTypeRepository->findMultipleByUid($this->settings['positionTypes'], 'title');
		$workingHours = $this->workingHoursRepository->findMultipleByUid($this->settings['workingHours'], 'title');
		$sectors = $this->sectorRepository->findMultipleByUid($this->settings['sectors'], 'title');
		$categories = $this->categoryRepository->findMultipleByUid($this->settings['categories'], 'title');
		$this->view->assignMultiple(
			array(
				'search' => $search,
				'positionTypes' => $positionTypes,
				'workingHours' => $workingHours,
				'sectors' => $sectors,
				'categories' => $categories,
				'overwriteDemand' => $overwriteDemand,
			)
		);
	}


	/**
	 * Displays the Search Result
	 *
	 * @param \array $search
	 * @param \array $overwriteDemand Demand overwriting the current settings. Optional.
	 * @return void
	 */
	public function searchResultAction($search = NULL, $overwriteDemand = NULL) {
		$demand = $this->createDemandFromSettings($this->settings);
		if($overwriteDemand) {
			$demand = $this->overwriteDemandObject($demand, $overwriteDemand);
		}

		if (!is_null($search)) {
			//@todo: throw exception if search fields are not set
			$searchObj = $this->objectManager->get('Webfox\\Placements\\Domain\\Model\\Dto\\Search');
			$searchObj->setFields($this->settings['position']['search']['fields']);
			$searchObj->setSubject($search['subject']);
		}
		$demand->setSearch($searchObj);

		$positions = $this->positionRepository->findDemanded($demand);
		if(!count($positions)) {
			$this->addFlashMessage(
				$this->translate('tx_placements.search.position.message.noSearchResult')
			);
		}
		$this->view->assignMultiple(
			array(
				'positions' => $positions,
				'search' => $search,
				'demand' => $demand,
				'requestArguments' => $this->requestArguments,
			)
		);
	}
	/**
	 * Create demand from settings
	 *
	 * @param \array $settings
	 * @return \Webfox\Placements\Domain\Model\Dto\PositionDemand
	 */
	public function createDemandFromSettings ($settings) {
		$demand = $this->objectManager->get('Webfox\\Placements\\Domain\\Model\\Dto\\PositionDemand');
		if ($settings['orderBy']) {
			$demand->setOrder($settings['orderBy'] . '|' . $settings['orderDirection']);
		}
		$demand->setPositionTypes($settings['positionTypes']);
		$demand->setWorkingHours($settings['workingHours']);
		$demand->setCategories($settings['categories']);
		$demand->setSectors($settings['sectors']);
		if($settings['constraintsConjunction'] !== '') {
			$demand->setConstraintsConjunction($settings['constraintsConjunction']);
		}
		if($settings['categoryConjunction'] !== '') {
			$demand->setCategoryConjunction($settings['categoryConjunction']);
		}
		if($settings['clientsPositionsOnly'] AND 
			$this->accessControlService->hasLoggedInClient()) {
			$demand->setClients(
							$this->accessControlService->
							getFrontendUser()->getClient()->getUid());
			$demand->setClientsPositionsOnly(TRUE);
		}
		$demand->setLimit($settings['limit']);
		return $demand;
	}

	/**
	 * Overwrites a given demand object by an propertyName =>  $propertyValue array
	 *
	 * @param \Webfox\Placements\Domain\Model\Dto\PositionDemand $demand
	 * @param \array $overwriteDemand
	 * @return \Webfox\Placements\Domain\Model\Dto\PositionDemand
	 */
	protected function overwriteDemandObject($demand, $overwriteDemand) {
		unset($overwriteDemand['orderByAllowed']);
		if($overwriteDemand['clientsPositionsOnly'] AND
			$this->accessControlService->hasLoggedInClient()) {
			$demand->setClients(
							(string)
							$this->
							accessControlService->
							getFrontendUser()->
							getClient()->getUid());
		} elseif ($overwriteDemand['clientsPositionOnly'] == '') {
			$demand->setClients('');
		}
		if (isset($overwriteDemand['orderBy']) AND 
				$overwriteDemand['orderBy'] != '') {
			$orderDirection = $this->settings['orderDirection'];
			if(isset($overwriteDemand['orderDirection'])  AND 
				$overwriteDemand['orderDirection'] !='') {
				$orderDirection = $overwriteDemand['orderDirection'];
			}
			$demand->setOrder($overwriteDemand['orderBy'] . '|' . $orderDirection);
		}
			
		if (!empty($overwriteDemand['search'])) {
			$searchObj = $this->createSearchObject(
				$overwriteDemand['search'], 
				$this->settings['position']['search']
			);
			$demand->setSearch($searchObj);
			unset($overwriteDemand['search']);
		}

		// demand for geoLocation and radius
		if ($demand->getSearch() AND 
				$demand->getSearch()->getRadius() 
				AND $demand->getSearch()->getLocation()) {
			$geoLocation = $this->geoCoder->getLocation($demand->getSearch()->getLocation());
			if($geoLocation) {
				$demand->setGeoLocation($geoLocation);
				$demand->setRadius($searchObj->getRadius());
			}
		}

		foreach ($overwriteDemand as $propertyName => $propertyValue) {
			\TYPO3\CMS\Extbase\Reflection\ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
		}

		return $demand;
	}

	/**
	 * A template method for displaying custom error flash messages, or to
	 * display no flash message at all on errors.
	 *
	 * @return string|boolean The flash message or FALSE if no flash message should be set
	 * @override \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
	 */
	 protected function getErrorFlashMessage() {
		return $this->translate('tx_placements.error'.'.position.'. $this->actionMethodName);
	 }

}

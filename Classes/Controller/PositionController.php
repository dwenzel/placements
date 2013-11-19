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
class PositionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * Position Repository
	 *
	 * @var \Webfox\Placements\Domain\Repository\PositionRepository
	 * @inject
	 */
	protected $positionRepository;

	/**
	 * Position Type Repository
	 * 
	 * @var \Webfox\Placements\Domain\Repository\PositionTypeRepository
	 * @inject
	 */
	protected $positionTypeRepository;

	/**
	 * Sector Repository
	 *
	 * @var \Webfox\Placements\Domain\Repository\SectorRepository
	 * @inject
	 */
	protected $sectorRepository;

	/**
	 * Organization Repository
	 *
	 * @var \Webfox\Placements\Domain\Repository\OrganizationRepository
	 * @inject
	 */
	protected $organizationRepository;

	/**
	 * Working Hours Repository
	 *
	 * @var \Webfox\Placements\Domain\Repository\WorkingHoursRepository
	 * @inject
	 */
	protected $workingHoursRepository;

	/**
	 * Category Repository
	 *
	 * @var \Webfox\Placements\Domain\Repository\CategoryRepository
	 * @inject
	 */
	protected $categoryRepository;

	/**
	 * Initialize Action
	 *
	 */
	 public function initializeUpdateAction() {
		if ($this->arguments->hasArgument('position')) {
			$this->arguments->getArgument('position')
			->getPropertyMappingConfiguration()
			->forProperty('entryDate')
			->setTypeConverterOption(
				'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter', 
				\TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 
				$this->settings['position']['edit']['entryDate']['format']
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
		$this->view->assign('positions', $positions);
	}

	/**
	 * action show
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $position
	 * @return void
	 */
	public function showAction(\Webfox\Placements\Domain\Model\Position $position) {
		$this->view->assign('position', $position);
	}

	/**
	 * action new
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $newPosition
	 * @ignorevalidation $newPosition
	 * @return void
	 */
	public function newAction(\Webfox\Placements\Domain\Model\Position $newPosition = NULL) {
		$this->view->assign('newPosition', $newPosition);
	}

	/**
	 * action create
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $newPosition
	 * @return void
	 */
	public function createAction(\Webfox\Placements\Domain\Model\Position $newPosition) {
		$this->positionRepository->add($newPosition);
		$this->flashMessageContainer->add('Your new Position was created.');
		$this->redirect('list');
	}

	/**
	 * action edit
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $position
	 * @ignorevalidation $position
	 * @return void
	 */
	public function editAction(\Webfox\Placements\Domain\Model\Position $position) {
		$positionTypes = $this->positionTypeRepository->findMultipleByUid($this->settings['positionTypes']);
		$workingHours = $this->workingHoursRepository->findMultipleByUid($this->settings['workingHours']);
		$categories = $this->categoryRepository->findMultipleByUid($this->settings['categories']);
		$sectors = $this->sectorRepository->findMultipleByUid($this->settings['sectors']);
		$organizations = $this->organizationRepository->findAll();
		$this->view->assignMultiple(array(
			'position' => $position,
			'workingHours' => $workingHours,
			'positionTypes' => $positionTypes,
			'categories' => $categories,
			'sectors' => $sectors,
			'organizations' => $organizations,
		));
	}

	/**
	 * action update
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $position
	 * @return void
	 */
	public function updateAction(\Webfox\Placements\Domain\Model\Position $position) {
		$this->positionRepository->update($position);
		$this->flashMessageContainer->add('Your Position was updated.');
		$this->redirect('list');
	}

	/**
	 * action delete
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $position
	 * @return void
	 */
	public function deleteAction(\Webfox\Placements\Domain\Model\Position $position) {
		$this->positionRepository->remove($position);
		$this->flashMessageContainer->add('Your Position was removed.');
		$this->redirect('list');
	}

	/**
	 * Quick Menu action
	 * @param \array $overwriteDemand Demand overwriting the current settings. Optional.
	 * @return void
	 */
	public function quickMenuAction(array $overwriteDemand = NULL) {
		// get session data
		//$sessionData = $GLOBALS['TSFE']->fe_user->getKey('ses', 'tx_placements_overwriteDemand');
		
		$positionTypes = $this->positionTypeRepository->findMultipleByUid($this->settings['positionTypes']);
		$workingHours = $this->workingHoursRepository->findMultipleByUid($this->settings['workingHours']);
		$sectors = $this->sectorRepository->findMultipleByUid($this->settings['sectors']);
		$categories = $this->categoryRepository->findMultipleByUid($this->settings['categories']);
		//$categories = $this->categoryRepository->findAll();
		$this->view->assignMultiple(
			array(
			    'positionTypes' => $positionTypes,
			    'workingHours' => $workingHours,
			    'sectors' => $sectors,
				'categories' => $categories,
			    //'overwriteDemand' => unserialize($sessionData)
			    'overwriteDemand' => $overwriteDemand
			    )
		);
	}

	/**
	 * Displays a Simple Search Form
	 *
	 * @param \Webfox\Placements\Domain\Model\Dto\Search $search
	 * @return void
	 */
	public function searchFormAction(\Webfox\Placements\Domain\Model\Dto\Search $search = NULL) {
		if (is_null($search)) {
			$search = $this->objectManager->get('Webfox\\Placements\\Domain\Model\\Dto\Search');
		}
		$this->view->assign('search', $search);
	}

	/**
	 * Displays the Extended Search Form
	 *
	 * @param \Webfox\Placements\Domain\Model\Dto\Search $search
	 * @param \array $overwriteDemand Demand overwriting the current settings. Optional.
	 * @return void
	 */
	public function extendedSearchFormAction(\Webfox\Placements\Domain\Model\Dto\Search $search = NULL, array $overwriteDemand = NULL) {
		if (is_null($search)) {
			$search = $this->objectManager->get('Webfox\\Placements\\Domain\Model\\Dto\Search');
		}
		$positionTypes = $this->positionTypeRepository->findMultipleByUid($this->settings['positionTypes']);
		$workingHours = $this->workingHoursRepository->findMultipleByUid($this->settings['workingHours']);
		$sectors = $this->sectorRepository->findMultipleByUid($this->settings['sectors']);
		$categories = $this->categoryRepository->findMultipleByUid($this->settings['categories']);
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
	 * @param \Webfox\Placements\Domain\Model\Dto\Search $search
	 * @param \array $overwriteDemand Demand overwriting the current settings. Optional.
	 * @return void
	 */
	public function searchResultAction(\Webfox\Placements\Domain\Model\Dto\Search $search = NULL, $overwriteDemand = NULL) {
		$demand = $this->createDemandFromSettings($this->settings);
		if($overwriteDemand) {
			$demand = $this->overwriteDemandObject($demand, $overwriteDemand);
		}

		if (!is_null($search)) {
			//@todo: throw exception if search fields are not set
			$search->setFields($this->settings['position']['search']['fields']);
		}
		$demand->setSearch($search);

		$positions = $this->positionRepository->findDemanded($demand);
		if(!count($positions)) {
			$this->flashMessageContainer->add(
				\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
				    'tx_placements.search.position.message.noSearchResult', 'placements'
				    )
				);
		}
		$this->view->assignMultiple(
			array(
				'positions' => $positions,
				'search' => $search,
				'demand' => $demand,
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

		foreach ($overwriteDemand as $propertyName => $propertyValue) {
			if(!empty($propertyValue)) {
				\TYPO3\CMS\Extbase\Reflection\ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
		    }
		}
		//store session data
		/*
		$sessionData = serialize($overwriteDemand);
		$GLOBALS['TSFE']->fe_user->setKey('ses', 'tx_placements_overwriteDemand', $sessionData);
		$GLOBALS['TSFE']->fe_user->storeSessionData();
		*/
		return $demand;
	}

}
?>
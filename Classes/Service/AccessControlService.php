<?php
namespace Webfox\Placements\Service;

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
class AccessControlService implements \TYPO3\CMS\Core\SingletonInterface {
		/**
		 * Frontend User Repository
		 *
		 * @var \Webfox\Placements\Domain\Repository\UserRepository
		 * @inject
		 */
		protected $userRepository;

		/**
		 * Frontend User Group Repository
		 *
		 * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserGroupRepository
		 * @inject
		 */
		protected $frontendUserGroupRepository;

		/**
		 * Frontend User
		 *
		 * @var \Webfox\Placements\Domain\Model\User
		 */
		protected $frontendUser;

		/**
		 * Configuration Manager
		 *
		 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
		 * @inject
		 */
		protected $configurationManager;

		/**
		 * TypoScript Settings
		 *
		 * @var \array
		 */
		protected $typoscriptSettings;

		/**
		 * Get Typoscript Settings
		 *
		 * @return \array
		 */
		public function getTyposcriptSettings() {
			if ($this->typoscriptSettings == NULL) {
				$this->typoscriptSettings = $this->configurationManager->getConfiguration(
					\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
				); 
			}
			return $this->typoscriptSettings;
		}

		/**
		 * Get Frontend User
		 *
		 * @return \Webfox\Placements\Domain\Model\User
		 */
		public function getFrontendUser() {
			if ($this->hasLoggedInFrontendUser() AND
					$this->frontendUser == NULL) {
				$this->frontendUser = $this->userRepository->findOneByUid(intval($GLOBALS['TSFE']->fe_user->user['uid']));
			}
			return $this->frontendUser;
		}

		/**
		 * Do we have a logged in feuser
		 * @return \boolean
		 */
		public function hasLoggedInFrontendUser() {
			return ($GLOBALS['TSFE']->loginUser == 1) ? TRUE : FALSE;
		}

		/**
		 * Do we have a logged in client?
		 *
		 * @return \boolean
		 */
		public function hasLoggedInClient() {
			if($this->getFrontendUser()) {
				return $this->getFrontendUser()->getClient() !== NULL ? TRUE : FALSE;
			} else {
				return FALSE;
			}
		}

		/**
		 * Does the client of a given object match with 
		 * current user's client?
		 * 
		 * @param \object $object
		 * @return \boolean
		 */
		public function matchesClient($object) {
			return (($object->getClient() !== NULL) AND 
				$this->hasLoggedInClient() AND
				($this->getFrontendUser()->getClient()->getUid() == $object->getClient()->getUid()));
		}
		 

		/** 
		 * Is current user allowed to edit
		 *
		 * @param \string $objectType Type of object to edit
		 * @return \boolean
		 */
		public function isAllowedToEdit($objectType) {
			return ($this->isAllowed('edit', $objectType) OR $this->isAllowed('admin', $objectType));
		}

		/** 
		 * Is current user allowed to create
		 *
		 * @param \string $objectType Type of object to edit
		 * @return \boolean
		 */
		public function isAllowedToCreate($objectType) {
			return ($this->isAllowed('create', $objectType) OR $this->isAllowed('admin', $objectType));
		}

		/** 
		 * Is current user allowed to delete
		 *
		 * @param \string $objectType Type of object to delete
		 * @return \boolean
		 */
		public function isAllowedToDelete($objectType) {
			return ($this->isAllowed('delete', $objectType) OR $this->isAllowed('admin', $objectType));
		}

		/** 
		 * Is current user allowed to perform a given action on a given object type
		 *
		 * @param \string $action Action to allow: edit,create,delete,admin
		 * @param \string $objectType Type of object to edit
		 * @return \boolean
		 */
		public function isAllowed($action, $objectType) {
			if ($this->hasLoggedInClient()) {
				$settings = $this->getTyposcriptSettings();
				switch ($action) {
					case 'edit':
						$accessGroup = 'editorGroup';
						break;
					case 'create':
						$accessGroup = 'creatorGroup';
						break;
					case 'delete':
						$accessGroup = 'deleteGroup';
						break;
					case 'admin':
						$accessGroup = 'adminGroup';
						break;
					default:
						return false;
				}
				$tsAccessGroups = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(
					',',
					$settings['security'][$objectType][$accessGroup]);
				foreach($tsAccessGroups as $tsAccessGroup) {
						$accessGroup = $this->frontendUserGroupRepository->findByUid($tsAccessGroup);
						if ($accessGroup != NULL && 
							$this->getFrontendUser()->getUsergroup()->contains($accessGroup)) {
								return TRUE;	
						}
				}
			}
			return FALSE;
		}
}
?>


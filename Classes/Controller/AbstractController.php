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
class AbstractController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

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
	 * Category Repository
	 *
	 * @var \Webfox\Placements\Domain\Repository\CategoryRepository
	 * @inject
	 */
	protected $categoryRepository;

	/**
	 * Access Control Service
	 *
	 * @var \Webfox\Placements\Service\AccessControlService
	 * @inject
	 */
	protected $accessControlService;

	/**
	 * Request Arguments
	 * @var \array
	 */
	protected $requestArguments = NULL;

	/*
	 * Referrer Arguments
	 * @var \array
	 */
	protected $referrerArguments = NULL;

	/**
	 * Initialize Action
	 */
	public function initializeAction() {
		$originalRequestArguments = $this->request->getArguments();
		$action = $originalRequestArguments['action'];
		unset($originalRequestArguments['action']);
		unset($originalRequestArguments['controller']);

		$this->requestArguments = array(
			'action' => $action ,
			'pluginName' => $this->request->getPluginName(),
			'controllerName' => $this->request->getControllerName(),
			'extensionName' => $this->request->getControllerExtensionName(),
			'arguments' => $originalRequestArguments,
		);
	}

	/**
	 * Upload file
	 */
	protected function uploadFile(&$fileName, $fileTmpName ) {
		$basicFileUtility = $this->objectManager->create('TYPO3\CMS\Core\Utility\File\BasicFileUtility');
		$absFileName = $basicFileUtility->getUniqueName( $fileName, \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('uploads/tx_placements'));
		$fileInfo = $basicFileUtility->getTotalFileInfo($absFileName);
		$fileName = $fileInfo['file'];
		\TYPO3\CMS\Core\Utility\GeneralUtility::upload_copy_move($fileTmpName, $absFileName); 
	}

	/**
 	 * update file property
 	 *
 	 * @param \TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject $object The object whose proptery should be updated
 	 * @param \string $propertyName The property which should be updated
 	 * @return void
 	 */
	protected function updateFileProperty($object, $propertyName) {
		if (\TYPO3\CMS\Extbase\Reflection\ObjectAccess::isPropertyGettable($object, $propertyName) AND
			\TYPO3\CMS\Extbase\Reflection\ObjectAccess::isPropertySettable($object, $propertyName)) {
			$file = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($object, $propertyName);
			
			if (is_array($file) AND !$file['error'] ) {
				$fileName = $file['name'];
				$this->uploadFile($fileName, $file['tmp_name']);
				\TYPO3\CMS\Extbase\Reflection\ObjectAccess::setProperty($object, $propertyName, $fileName);
			} else {
				$object->_memorizeCleanState($propertyName);
				if($file['error']) {
					switch($file['error']) {
						case 1:
							$errMsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_placements.error.upload.1', 'placements');
							break;
						case 2:
							$errMsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_placements.error.upload.2', 'placements');
							break;
						case 3:
							$errMsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_placements.error.upload.3', 'placements');
							break;
						case 4:
							$errMsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_placements.error.upload.4', 'placements');
							break;
						case 6:
							$errMsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_placements.error.upload.6', 'placements');
							break;
						case 7:
							$errMsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_placements.error.upload.7', 'placements');
							break;
						case 8:
							$errMsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_placements.error.upload.8', 'placements');
							break;
						default:
							$errMsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_placements.error.upload.unknown', 'placements');
					}
					$this->flashMessageContainer->add($errMsg);
				}
			}
		}
	}
}
?>


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
	 * Geocoder Utility
	 *
	 * @var \Webfox\Placements\Utility\Geocoder
	 * @inject
	 */
	protected $geoCoder;

	/**
	 * Request Arguments
	 * @var \array
	 */
	protected $requestArguments = NULL;

	/*
	 * Referrer Arguments
	 * @var \array
	 */
	protected $referrerArguments = array();

	/**
	 * @var string
	 */
	protected $entityNotFoundMessage = 'The requested entity could not be found';

	/**
	 * @var string
	 */
	protected $unknownErrorMessage = 'An unknown error occured.';

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
		if($this->request->hasArgument('referrerArguments') AND
			is_array($this->request->getArgument('referrerArguments'))) {
		    $this->referrerArguments = $this->request->getArgument('referrerArguments');
		} else {
		    $this->referrerArguments = array();
		}
	}

	/**
	 * Upload file
	 */
	protected function uploadFile($fileName, $fileTmpName ) {
		$basicFileUtility = $this->objectManager->get('TYPO3\CMS\Core\Utility\File\BasicFileUtility');
		$absFileName = $basicFileUtility->getUniqueName( $fileName, \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('uploads/tx_placements'));
		$fileInfo = $basicFileUtility->getTotalFileInfo($absFileName);
		$realFileName = $fileInfo['file'];
		\TYPO3\CMS\Core\Utility\GeneralUtility::upload_copy_move($fileTmpName, $absFileName);
		return $realFileName;
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
				$realFileName = $this->uploadFile($fileName, $file['tmp_name']);
				\TYPO3\CMS\Extbase\Reflection\ObjectAccess::setProperty($object, $propertyName, $realFileName);
			} else {
				$object->_memorizeCleanState($propertyName);
				$error = $file['error'];
				$knownErrorCodes = array(1,2,3,4,6,7,8);
				$key = 'tx_placements.error.upload.unknown';
				if(in_array($error, $knownErrorCodes)) {
							$key = 'tx_placements.error.upload.' . $file['error'];
				}

				$errorMessage = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($key, 'placements');
				if($errorMessage) {
					$this->addFlashMessage($errorMessage);
				} else {
					$this->addFlashMessage($key);
				}
			}
		}
	}

	/**
	* @param \TYPO3\CMS\Extbase\Mvc\RequestInterface $request
	* @param \TYPO3\CMS\Extbase\Mvc\ResponseInterface $response
	* @return void
	* @throws \Exception
	* @override \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
	*/
	public function processRequest(\TYPO3\CMS\Extbase\Mvc\RequestInterface $request, \TYPO3\CMS\Extbase\Mvc\ResponseInterface $response) {
		try{
			parent::processRequest($request, $response);
		}
		catch(\Exception $exception) {
			// If the property mapper did throw a \TYPO3\CMS\Extbase\Property\Exception, because it was unable to find the requested entity, call the page-not-found handler.
			$previousException = $exception->getPrevious();
			if (($exception instanceof \TYPO3\CMS\Extbase\Property\Exception) && (($previousException instanceof \TYPO3\CMS\Extbase\Property\Exception\TargetNotFoundException) || ($previousException instanceof \TYPO3\CMS\Extbase\Property\Exception\InvalidSourceException))) {
				$configuration = isset($this->settings[strtolower($request->getControllerName())]['detail']['errorHandling'])? $this->settings[strtolower($request->getControllerName())]['detail']['errorHandling'] : NULL;
				if($configuration ) {
					$this->handleEntityNotFoundError($configuration);
				}
			}
			throw $exception;
		}
	}

	/**
	 * Error handling if requested entity is not found
	 *
	 * @param \string $configuration Configuration for handling
	 */
	public function handleEntityNotFoundError($configuration) {
		if(empty($configuration)) {
			return;
		}
		$configuration = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $configuration);
		switch($configuration[0]) {
			case 'redirectToListView':
				$this->redirect('list');
				break;
			case 'redirectToPage':
				if (count($configuration) === 1 || count($configuration) > 3) {
					$msg = sprintf('If error handling "%s" is used, either 2 or 3 arguments, splitted by "," must be used', $configuration[0]);
					throw new \InvalidArgumentException($msg);
				}
				$this->uriBuilder->reset();
				$this->uriBuilder->setTargetPageUid($configuration[1]);
				$this->uriBuilder->setCreateAbsoluteUri(TRUE);
				if (\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SSL')) {
					$this->uriBuilder->setAbsoluteUriScheme('https');
				}
				$url = $this->uriBuilder->build();
				if (isset($configuration[2])) {
					$this->redirectToUri($url, 0, (int)$configuration[2]);
				} else {
					$this->redirectToUri($url);
				}
				break;
			case 'pageNotFoundHandler':
					$GLOBALS['TSFE']->pageNotFoundAndExit($this->entityNotFoundMessage);
				break;
			default:
		}
	}

	/**
	 * Creates a search object from given settings
	 *
	 * @param \array $searchRequest An array with the search request
	 * @param \array $settings Settings for search
	 * @return \Webfox\Placements\Domain\Model\Dto\Search $search
	 */
	public function createSearchObject($searchRequest, $settings) {
		$searchObject = $this->objectManager->get('Webfox\Placements\Domain\Model\Dto\Search');

		if(isset($searchRequest['subject']) AND isset($settings['fields'])) {
			$searchObject->setFields($settings['fields']);
			$searchObject->setSubject($searchRequest['subject']);
		}
		if (isset($searchRequest['location']) AND isset($searchRequest['radius'])) {
			$searchObject->setLocation($searchRequest['location']);
			$searchObject->setRadius($searchRequest['radius']);
		}
		return $searchObject;
	}
	/**
	 * @return void
	 * @override \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
	 */
/*	protected function callActionMethod() {
		try {
			parent::callActionMethod();
		}
		catch(\Exception $exception) {
			// This enables you to trigger the call of TYPO3s page-not-found handler by throwing \TYPO3\CMS\Core\Error\Http\PageNotFoundException
			if ($exception instanceof \TYPO3\CMS\Core\Error\Http\PageNotFoundException) {
				$GLOBALS['TSFE']->pageNotFoundAndExit($this->entityNotFoundMessage);
			}

			// $GLOBALS['TSFE']->pageNotFoundAndExit has not been called, so the exception is of unknown type.
			\VendorName\ExtensionName\Logger\ExceptionLogger::log($exception, $this->request->getControllerExtensionKey(), \VendorName\ExtensionName\Logger\ExceptionLogger::SEVERITY_FATAL_ERROR);
			// If the plugin is configured to do so, we call the page-unavailable handler.
			if (isset($this->settings['usePageUnavailableHandler']) && $this->settings['usePageUnavailableHandler']) {
				$GLOBALS['TSFE']->pageUnavailableAndExit($this->unknownErrorMessage, 'HTTP/1.1 500 Internal Server Error');
			}
			// Else we append the error message to the response. This causes the error message to be displayed inside the normal page layout. WARNING: the plugins output may gets cached.
			if ($this->response instanceof \TYPO3\CMS\Extbase\Mvc\Web\Response) {
				$this->response->setStatus(500);
			}
			$this->response->appendContent($this->unknownErrorMessage);
		}
	}
	*/
}
?>


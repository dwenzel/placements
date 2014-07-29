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

				$errorMessage = $this->translate($key);
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
	 * Translate a given key
	 *
	 * @param \string $key
	 * @param \string $extension
	 * @param \array $arguments
	 * @codeCoverageIgnore
	 */
	public function translate($key, $extension='placements', $arguments=NULL) {
		return \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($key, $extension, $arguments);
	}
}

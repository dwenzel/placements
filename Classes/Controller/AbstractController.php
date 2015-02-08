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
	 * Position Repository
	 *
	 * @var \Webfox\Placements\Domain\Repository\PositionRepository
	 * @inject
	 */
	protected $positionRepository;

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
		$this->setRequestArguments();
		$this->setReferrerArguments();
	}

	/**
	 * Set request arguments
	 *
	 * @return void
	 */
	protected function setRequestArguments() {
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
	 * Set referrer arguments
	 *
	 * @return void
	 */
	protected function setReferrerArguments() {
		if($this->request->hasArgument('referrerArguments') AND
			is_array($this->request->getArgument('referrerArguments'))) {
		    $this->referrerArguments = $this->request->getArgument('referrerArguments');
		} else {
		    $this->referrerArguments = array();
		}
	}

	/**
	 * Get mapping configuration for property
	 *
	 * Returns the property mapping configuration for a given
	 * argument / property combination 
	 * or false if arguments does not have such an argument
	 *
	 * @param \string $argumentName Name of argument
	 * @param \string $propertyName Name of the property e.g. 'foo.bar'
	 * @return \TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration|NULL
	 */
	protected function getMappingConfigurationForProperty($argumentName, $propertyName) {
		if($this->arguments->hasArgument($argumentName)) {
			$mappingConfiguration = $this->arguments
				->getArgument($argumentName)
				->getPropertyMappingConfiguration()
				->forProperty($propertyName);
		}
		return $mappingConfiguration;
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

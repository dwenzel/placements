<?php
namespace Webfox\Placements\ViewHelpers\Security;
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
 * Abstract base class for security ViewHelpers.
 *
 * @category    ViewHelpers
 * @package     placements
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author      Dirk Wenzel <wenzel@webfox01.de>
 */
abstract class AbstractSecurityViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * Access Control Service
	 *
	 * @var \Webfox\Placements\Service\AccessControlService
	 * @inject
	 */
	protected $accessControlService;

	/**
	 * Initialize arguments
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('action', 'string', 'Action which should be allowed. Optional.');
		$this->registerArgument('type', 'string', 'Object type for which the action should be allowed. Optional.');
		$this->registerArgument('object', 'mixed', 'Object for which acces right should be evaluated. Optional.');
		$this->registerArgument('matchClient', 'boolean', 'whether the current users client must match the objects client');
	}

	/**
	 * Returns the then child
	 *
	 * @return \string
	 */
	public function renderThenChild() {
		return parent::renderThenChild();
	}

	/** Evaluate access rights
	 *
	 * @return \boolean
	 */
	 protected function evaluate() {
	 	$evaluations = array();
	 	$object = $this->arguments['object'];
	 	$objectType = 'unknown';
	 	if (is_object($object) AND $this->arguments['matchClient'] == TRUE) {
	 		$objectType = strtolower (end(\TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('\\', get_class($object))));
	 		$evaluations['matchClient'] = $this->accessControlService->matchesClient($object);
	 	}
	 	if ($objectType == 'unknown' AND $this->arguments['type']){
	 		$objectType = $this->arguments['type'];
	 	}
	 	if ($this->arguments['action']) {
	 		$evaluations['action'] = $this->accessControlService->isAllowed($this->arguments['action'], $objectType);
	 	}
	 	return (count($evaluations) === array_sum($evaluations));
	 }
}

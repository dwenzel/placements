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
	 * Injects the Configuration Manager and is initializing the framework settings
	 *
	 * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager Instance of the Configuration Manager
	 * @return void
	 */
	public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;

		$tsSettings = $this->configurationManager->getConfiguration(
				\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
			);
		$originalSettings = $this->configurationManager->getConfiguration(
				\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
			);

		\TYPO3\CMS\Core\Utility\GeneralUtility::devlog('tsSettings', 'placements', 1 , $tsSettings);
		\TYPO3\CMS\Core\Utility\GeneralUtility::devlog('originialSettings', 'placements', 1 , $originalSettings);
			// start override
		if (isset($tsSettings['settings']['overrideFlexformSettingsIfEmpty'])) {
			//$overrideIfEmpty = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $tsSettings['settings']['overrideFlexformSettingsIfEmpty'], TRUE);
		/*	
		foreach ($overrideIfEmpty as $key) {
					// if flexform setting is empty and value is available in TS
				if ((!isset($originalSettings[$key]) || empty($originalSettings[$key]))
						&& isset($tsSettings['settings'][$key])) {
					$originalSettings[$key] = $tsSettings['settings'][$key];
				}
			}*/
		$resultingSettings = \TYPO3\CMS\Core\Utility\GeneralUtility::array_merge_recursive_overrule(
			$tsSettings, $originalSettings, FALSE, FALSE);
		\TYPO3\CMS\Core\Utility\GeneralUtility::devlog('resultingSettings', 'placements', 1 , $resultingSettings);
			
		}

/*			// Use stdWrap for given defined settings
		if (isset($originalSettings['useStdWrap']) && !empty($originalSettings['useStdWrap'])) {
			if (class_exists('\TYPO3\CMS\Extbase\Service\TypoScriptService')) {
				$typoScriptService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\TYPO3\CMS\Extbase\Service\TypoScriptService');
			} else {
				$typoScriptService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\TYPO3\CMS\Extbase\Utility\TypoScript');
			}
			$typoScriptArray = $typoScriptService->convertPlainArrayToTypoScriptArray($originalSettings);
			$stdWrapProperties = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $originalSettings['useStdWrap'], TRUE);
			foreach ($stdWrapProperties as $key) {
				if (is_array($typoScriptArray[$key . '.'])) {
					$originalSettings[$key] = $this->configurationManager->getContentObject()->stdWrap(
							$originalSettings[$key],
							$typoScriptArray[$key . '.']
					);
				}
			}
		}

		$this->settings = $originalSettings;
*/
		$this->settings = $resultingSettings['settings'];
	}

}
?>

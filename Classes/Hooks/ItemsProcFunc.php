<?php
namespace Webfox\Placements\Hooks;
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
 * @package placements
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ItemsProcFunc {
	/**
	* Itemsproc function to extend the selection of templateLayouts in the plugin
	*
	* @param array &$config configuration array
	* @param \TYPO3\CMS\Backend\Form\FormEngine $parentObject parent object
	* @return void
	*/
	public function user_templateLayout(array &$config, \TYPO3\CMS\Backend\Form\FormEngine $parentObject) {
		// Check if the layouts are extended by ext_tables
		if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['placements']['templateLayouts'])
			&& is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['placements']['templateLayouts'])) {

			// Add every item
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['placements']['templateLayouts'] as $layouts) {
				$additionalLayout = array(
					$GLOBALS['LANG']->sL($layouts[0], TRUE),
					$layouts[1]
				);
				array_push($config['items'], $additionalLayout);
			}
		}

		// Add tsconfig values
		if (is_numeric($config['row']['pid'])) {
			$pagesTsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($config['row']['pid']);
			if (isset($pagesTsConfig['tx_placements.']['templateLayouts.']) && is_array($pagesTsConfig['tx_placements.']['templateLayouts.'])) {
				// Add every item
				foreach ($pagesTsConfig['tx_placements.']['templateLayouts.'] as $key => $label) {
					$additionalLayout = array(
						$GLOBALS['LANG']->sL($label, TRUE),
						$key
					);
					array_push($config['items'], $additionalLayout);
				}
			}
		}
	}
}

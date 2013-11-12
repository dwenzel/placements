<?php
namespace Webfox\Placements\Hooks;

/***************************************************************
*  Copyright notice
*
*  (c) 2010 Georg Ringer <typo3@ringerge.org>
*  (c) 2013 Dirk Wenzel <wenzel@webfox01.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
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
 * Hook into t3lib_befunc to change flexform behaviour
 * depending on action selection
 * Originally written by Georg Ringer for tx_news.
 * adapted for tx_placements by Dirk Wenzel.
 *
 * @package TYPO3
 * @subpackage tx_placements
 */
class T3libBefunc {

	/**
	 * Fields which are removed in position list view
	 *
	 * @var \array
	 */
	public $removedFieldsInPositionListView = array(
			'sDEF' => '',
			'constraints' => 'showWorkingHours,showSectors,showPositionTypes,constraintsConjunction,showConjunctionSelector',
		);

	/**
	 * Fields which are removed in position quick menu view
	 *
	 * @var \array
	 */
	public $removedFieldsInPositionQuickMenuView = array(
			'sDEF' => '',
			'constraints' => '',
		);

	/**
	 * Fields which are removed in position searchForm view
	 *
	 * @var \array
	 */
	public $removedFieldsInPositionSearchFormView = array(
			'sDEF' => 'orderBy,orderDirection',
			'constraints' => 'workingHours,sectors,positionTypes,showWorkingHours,showSectors,showPositionTypes,constraintsConjunction,showConjunctionSelector,categories,categoryConjunction,limit',
		);

	/**
	 * Fields which are removed in position search result view
	 *
	 * @var \array
	 */
	public $removedFieldsInPositionSearchResultView = array(
			'sDEF' => '',
			'constraints' => 'workingHours,sectors,positionTypes,showWorkingHours,showSectors,showPositionTypes,constraintsConjunction,showConjunctionSelector,categories,categoryConjunction',
		);

	/**
	 * Fields which are removed in user list view
	 *
	 * @var \array
	 */
	public $removedFieldsInUserListView = array(
			'sDEF' => '',
			'constraints' => 'workingHours,sectors,positionTypes,showWorkingHours,showSectors,showPositionTypes,constraintsConjunction,showConjunctionSelector',
		);

	/**
	 * Fields which are removed in application list view
	 *
	 * @var \array
	 */
	public $removedFieldsInApplicationListView = array(
			'sDEF' => '',
			'constraints' => 'workingHours,sectors,positionTypes,showWorkingHours,showSectors,showPositionTypes,constraintsConjunction,showConjunctionSelector',
		);


	/**
	 * Hook function of t3lib_befunc
	 * It is used to change the flexform for placements
	 *
	 * @param \array &$dataStructure Flexform structure
	 * @param \array $conf some strange configuration
	 * @param \array $row row of current record
	 * @param \string $table table name
	 * @param \string $fieldName some strange field name
	 * @return void
	 */
	public function getFlexFormDS_postProcessDS(&$dataStructure, $conf, $row, $table, $fieldName) {
		if ($table === 'tt_content' && $row['list_type'] === 'placements_placements' && is_array($dataStructure)) {
			$this->updateFlexforms($dataStructure, $row);
		}
	}

	/**
	 * Update flexform configuration if a action is selected
	 *
	 * @param \array|\string &$dataStructure flexform structure
	 * @param \array $row row of current record
	 * @return void
	 */
	protected function updateFlexforms(array &$dataStructure, array $row) {
		$selectedView = '';

			// get the first selected action
		$flexformSelection = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($row['pi_flexform']);
		if (is_array($flexformSelection) && is_array($flexformSelection['data'])) {
			$selectedView = $flexformSelection['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'];
			if (!empty($selectedView)) {
				$actionParts = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(';', $selectedView, TRUE);
				$selectedView = $actionParts[0];
			}

			// new plugin element
		} elseif (\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($row['uid'], 'NEW')) {
				// use List as starting view
			$selectedView = 'Position->list';
		}

		if (!empty($selectedView)) {
				// Modify the flexform structure depending on the first found action
			switch ($selectedView) {
				case 'Position->list':
					$this->deleteFromStructure($dataStructure, $this->removedFieldsInPositionListView);
					break;
				case 'User->list':
					$this->deleteFromStructure($dataStructure, $this->removedFieldsInUserListView);
					break;
				case 'Application->list':
					$this->deleteFromStructure($dataStructure, $this->removedFieldsInApplicationListView);
					break;
				case 'Position->searchResult':
					$this->deleteFromStructure($dataStructure, $this->removedFieldsInPositionSearchResultView);
					break;
				case 'Position->searchForm':
					$this->deleteFromStructure($dataStructure, $this->removedFieldsInPositionSearchFormView);
					unset($dataStructure['sheets']['constraints']);
					break;
				case 'Position->quickMenu':
					$this->deleteFromStructure($dataStructure, $this->removedFieldsInPositionQuickMenuView);
					break;
				default:
			}

			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['placements']['Hooks/T3libBefunc.php']['updateFlexforms'])) {
				$params = array(
					'selectedView' => $selectedView,
					'dataStructure' => &$dataStructure,
				);
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['placements']['Hooks/T3libBefunc.php']['updateFlexforms'] as $reference) {
					\TYPO3\CMS\Core\Utility\GeneralUtility::callUserFunction($reference, $params, $this);
				}
			}
		}
	}

	/**
	 * Remove fields from flexform structure
	 *
	 * @param \array &$dataStructure flexform structure
	 * @param \array $fieldsToBeRemoved fields which need to be removed
	 * @return void
	 */
	protected function deleteFromStructure(array &$dataStructure, array $fieldsToBeRemoved) {
		foreach ($fieldsToBeRemoved as $sheetName => $sheetFields) {
			$fieldsInSheet = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $sheetFields, TRUE);

			foreach ($fieldsInSheet as $fieldName) {
				unset($dataStructure['sheets'][$sheetName]['ROOT']['el']['settings.' . $fieldName]);
			}
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/placements/Classes/Hooks/T3libBefunc.php']) {
	require_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/placements/Classes/Hooks/T3libBefunc.php']);
}

?>

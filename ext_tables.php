<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'placements',
	'Placements',
	'Placements'
);

\TYPO3\CMS\Core\Utility\GeneralUtility::requireOnce(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('placements') . 'Classes/Hooks/ItemsProcFunc.php');
$pluginSignature = str_replace('_','','placements') . '_placements';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . 'placements' . '/Configuration/FlexForms/flexform_placements.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('placements', 'Configuration/TypoScript', 'Placement Service');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_position', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_position.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_position');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_application', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_application.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_application');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_organization', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_organization.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_organization');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_resume', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_resume.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_resume');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_section', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_section.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_section');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_client', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_client.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_client');

$tmp_placements_columns = array(

	'resumes' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_user.resumes',
		'config' => array(
			'type' => 'inline',
			'foreign_table' => 'tx_placements_domain_model_resume',
			'foreign_field' => 'user',
			'maxitems'      => 9999,
			'appearance' => array(
				'collapseAll' => 0,
				'levelLinksPosition' => 'top',
				'showSynchronizationLink' => 1,
				'showPossibleLocalizationRecords' => 1,
				'showAllLocalizationLink' => 1
			),
		),
	),
	'applications' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_user.applications',
		'config' => array(
			'type' => 'inline',
			'foreign_table' => 'tx_placements_domain_model_application',
			'foreign_field' => 'user',
			'maxitems'      => 9999,
			'appearance' => array(
				'collapseAll' => 0,
				'levelLinksPosition' => 'top',
				'showSynchronizationLink' => 1,
				'showPossibleLocalizationRecords' => 1,
				'showAllLocalizationLink' => 1
			),
		),
	),
	'client' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_user.client',
		'config' => array(
			'type' => 'select',
			'foreign_table' => 'tx_placements_domain_model_client',
			'minitems' => 0,
			'maxitems' => 1,
			'items' => array(array('',NULL)),
		),
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_users',$tmp_placements_columns);

$TCA['fe_users']['columns'][$TCA['fe_users']['ctrl']['type']]['config']['items'][] = array('LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:fe_users.tx_extbase_type.Tx_Placements_User','Tx_Placements_User');

$TCA['fe_users']['types']['Tx_Placements_User']['showitem'] = $TCA['fe_users']['types']['1']['showitem'];
$TCA['fe_users']['types']['Tx_Placements_User']['showitem'] .= ',--div--;LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_user,';
$TCA['fe_users']['types']['Tx_Placements_User']['showitem'] .= 'resumes, applications, client';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_profil', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_profil.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_profil');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_positiontype', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_positiontype.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_positiontype');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_workinghours', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_workinghours.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_workinghours');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_sector', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_sector.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_sector');

## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder
//extend frontend user
$TCA['fe_users']['types']['Tx_Placements_User']['showitem'] = $TCA['fe_users']['types']['Tx_Extbase_Domain_Model_FrontendUser']['showitem'];
$TCA['fe_users']['types']['Tx_Placements_User']['showitem'] .= ',--div--;LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_user,';
$TCA['fe_users']['types']['Tx_Placements_User']['showitem'] .= 'resumes, applications, client';


// make positions categorizable
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
	'placements',
	'tx_placements_domain_model_position',
	$fieldName = 'categories',
	$options = array()
);
// make organizations categorizable
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
	'placements',
	'tx_placements_domain_model_organization',
	$fieldName = 'categories',
	$options = array()
);


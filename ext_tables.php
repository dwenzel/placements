<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Placements',
	'Placements'
);

\TYPO3\CMS\Core\Utility\GeneralUtility::requireOnce(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Hooks/ItemsProcFunc.php');
$pluginSignature = str_replace('_','',$_EXTKEY) . '_placements';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_placements.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Placement Service');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_position', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_position.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_position');
$TCA['tx_placements_domain_model_position'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'requestUpdate' => 'fixed_term',
		'searchFields' => 'title,identifier,summary,description,entry_date,fixed_term,duration,zip,city,payment,contact,link,organization,client,type,categories,working_hours,sectors,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Position.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_placements_domain_model_position.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_application', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_application.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_application');
$TCA['tx_placements_domain_model_application'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_application',
		'label' => 'introduction',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'introduction,text,file,position,resume,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Application.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_placements_domain_model_application.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_organization', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_organization.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_organization');
$TCA['tx_placements_domain_model_organization'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_organization',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,identifier,description,sectors,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Organization.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_placements_domain_model_organization.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_resume', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_resume.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_resume');
$TCA['tx_placements_domain_model_resume'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_resume',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,introduction,sections,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Resume.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_placements_domain_model_resume.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_section', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_section.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_section');
$TCA['tx_placements_domain_model_section'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_section',
		'label' => 'begin',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'begin,end,position,description,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Section.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_placements_domain_model_section.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_client', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_client.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_client');
$TCA['tx_placements_domain_model_client'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_client',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'name,identifier,description,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Client.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_placements_domain_model_client.gif'
	),
);

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

t3lib_extMgm::addTCAcolumns('fe_users',$tmp_placements_columns);

$TCA['fe_users']['columns'][$TCA['fe_users']['ctrl']['type']]['config']['items'][] = array('LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:fe_users.tx_extbase_type.Tx_Placements_User','Tx_Placements_User');

$TCA['fe_users']['types']['Tx_Placements_User']['showitem'] = $TCA['fe_users']['types']['1']['showitem'];
$TCA['fe_users']['types']['Tx_Placements_User']['showitem'] .= ',--div--;LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_user,';
$TCA['fe_users']['types']['Tx_Placements_User']['showitem'] .= 'resumes, applications, client';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_profil', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_profil.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_profil');
$TCA['tx_placements_domain_model_profil'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_profil',
		'label' => 'user',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'user,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Profil.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_placements_domain_model_profil.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_positiontype', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_positiontype.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_positiontype');
$TCA['tx_placements_domain_model_positiontype'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_positiontype',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,description,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/PositionType.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_placements_domain_model_positiontype.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_workinghours', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_workinghours.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_workinghours');
$TCA['tx_placements_domain_model_workinghours'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_workinghours',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,description,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/WorkingHours.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_placements_domain_model_workinghours.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_placements_domain_model_sector', 'EXT:placements/Resources/Private/Language/locallang_csh_tx_placements_domain_model_sector.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_placements_domain_model_sector');
$TCA['tx_placements_domain_model_sector'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_sector',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,description,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Sector.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_placements_domain_model_sector.gif'
	),
);

## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder
//extend frontend user
$TCA['fe_users']['types']['Tx_Placements_User']['showitem'] = $TCA['fe_users']['types']['Tx_Extbase_Domain_Model_FrontendUser']['showitem'];
$TCA['fe_users']['types']['Tx_Placements_User']['showitem'] .= ',--div--;LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_user,';
$TCA['fe_users']['types']['Tx_Placements_User']['showitem'] .= 'resumes, applications, client';


// make positions categorizable
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
	$_EXTKEY,
	'tx_placements_domain_model_position',
	$fieldName = 'categories',
	$options = array()
);
// make organizations categorizable
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
	$_EXTKEY,
	'tx_placements_domain_model_organization',
	$fieldName = 'categories',
	$options = array()
);
?>


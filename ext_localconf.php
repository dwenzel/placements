<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
$settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
if (!empty($settings['includeJQuery'])) {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
		'<INCLUDE_TYPOSCRIPT: source="FILE:EXT:placements/Resources/Private/TypoScript/jQuery.ts>');
}

if (!empty($settings['includeGoogleMaps'])) {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
		'<INCLUDE_TYPOSCRIPT: source="FILE:EXT:'. $_EXTKEY . '/Resources/Private/TypoScript/googleMaps.ts>');
}
if (!empty($settings['includeJavaScript'])) {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
		'<INCLUDE_TYPOSCRIPT: source="FILE:EXT:'. $_EXTKEY . '/Resources/Private/TypoScript/javascript.ts>');
}
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Webfox.' . $_EXTKEY,
	'Placements',
	array(
		'Position' => 'list, show, new, create, edit, update, delete, quickMenu,ajaxList',
		'User' => 'list, show, new, create, edit, update, delete',
		'Application' => 'list, show, new, create, edit, update, delete',
		'Organization' => 'list, show, new, create, edit, update, delete',
		
	),
	// non-cacheable actions
	array(
		'Position' => 'create, update, delete, quickMenu',
		'User' => 'create, update, delete',
		'Application' => 'create, update, delete',
		'Organization' => 'create, update, delete',
		
	)
);

// Modify flexform values
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['getFlexFormDSClass'][$_EXTKEY] =
	'EXT:' . $_EXTKEY . '/Classes/Hooks/T3libBefunc.php:Webfox\Placements\Hooks\T3libBefunc';

# include eid dispatcher
$TYPO3_CONF_VARS['FE']['eID_include']['placementsAjax'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY).'Classes/Utility/EidDispatcher.php';
?>

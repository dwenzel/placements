<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Webfox.' . $_EXTKEY,
	'User',
	array(
		'User' => 'list, show, new, edit',
		
	),
	// non-cacheable actions
	array(
		'User' => 'create, update, delete',
		
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Webfox.' . $_EXTKEY,
	'Position',
	array(
		'Position' => 'list, show, new, create, edit, update, delete',
		
	),
	// non-cacheable actions
	array(
		'Position' => 'create, update, delete',
		
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Webfox.' . $_EXTKEY,
	'Application',
	array(
		'Application' => 'list, show, new, create, edit, update, delete',
		
	),
	// non-cacheable actions
	array(
		'Application' => 'create, update, delete',
		
	)
);

## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder
?>
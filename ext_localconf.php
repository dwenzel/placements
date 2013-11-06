<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Webfox.' . $_EXTKEY,
	'Placements',
	array(
		'Position' => 'list, show, new, create, edit, update, delete, quickMenu',
		'User' => 'list, show, new, create, edit, update, delete',
		'Application' => 'list, show, new, create, edit, update, delete',
		
	),
	// non-cacheable actions
	array(
		'Position' => 'create, update, delete, quickMenu',
		'User' => 'create, update, delete',
		'Application' => 'create, update, delete',
		
	)
);

?>
<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_placements_domain_model_position'] = array(
	'ctrl' => $TCA['tx_placements_domain_model_position']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, identifier, summary, description, entry_date, fixed_term, duration, zip, city, payment, contact, link, organization, client, type, categories, working_hours, sectors',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, title, identifier, summary, description, entry_date, fixed_term, duration, zip, city, payment, contact, link, organization, client, type, categories, working_hours, sectors,--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_placements_domain_model_position',
				'foreign_table_where' => 'AND tx_placements_domain_model_position.pid=###CURRENT_PID### AND tx_placements_domain_model_position.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'identifier' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position.identifier',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'summary' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position.summary',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			),
		),
		'description' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position.description',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim',
				'wizards' => array(
					'RTE' => array(
						'icon' => 'wizard_rte2.gif',
						'notNewRecords'=> 1,
						'RTEonly' => 1,
						'script' => 'wizard_rte.php',
						'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
						'type' => 'script'
					)
				)
			),
			'defaultExtras' => 'richtext:rte_transform[flag=rte_enabled|mode=ts]',
		),
		'entry_date' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position.entry_date',
			'config' => array(
				'type' => 'input',
				'size' => 7,
				'eval' => 'date',
				'checkbox' => 1,
				'default' => time()
			),
		),
		'fixed_term' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position.fixed_term',
			'config' => array(
				'type' => 'check',
				'default' => 0
			),
		),
		'duration' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position.duration',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			),
		),
		'zip' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position.zip',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'city' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position.city',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'payment' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position.payment',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			),
		),
		'contact' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position.contact',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim',
				'wizards' => array(
					'RTE' => array(
						'icon' => 'wizard_rte2.gif',
						'notNewRecords'=> 1,
						'RTEonly' => 1,
						'script' => 'wizard_rte.php',
						'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
						'type' => 'script'
					)
				)
			),
			'defaultExtras' => 'richtext:rte_transform[flag=rte_enabled|mode=ts]',
		),
		'link' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position.link',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'organization' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position.organization',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_placements_domain_model_organization',
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
		'client' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position.client',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_placements_domain_model_client',
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
		'type' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position.type',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_placements_domain_model_positiontype',
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
		'categories' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position.categories',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'sys_category',
				'foreign_field' => 'position',
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
		'working_hours' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position.working_hours',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_placements_domain_model_workinghours',
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
		'sectors' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:placements/Resources/Private/Language/locallang_db.xlf:tx_placements_domain_model_position.sectors',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_placements_domain_model_sector',
				'MM' => 'tx_placements_position_sector_mm',
				'size' => 10,
				'autoSizeMax' => 30,
				'maxitems' => 9999,
				'multiple' => 0,
				'wizards' => array(
					'_PADDING' => 1,
					'_VERTICAL' => 1,
					'edit' => array(
						'type' => 'popup',
						'title' => 'Edit',
						'script' => 'wizard_edit.php',
						'icon' => 'edit2.gif',
						'popup_onlyOpenIfSelected' => 1,
						'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
						),
					'add' => Array(
						'type' => 'script',
						'title' => 'Create new',
						'icon' => 'add.gif',
						'params' => array(
							'table' => 'tx_placements_domain_model_sector',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'prepend'
							),
						'script' => 'wizard_add.php',
					),
				),
			),
		),
	),
);

## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder
?>
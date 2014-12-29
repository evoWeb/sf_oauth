<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}



if (TYPO3_MODE === 'BE') {
	/**
	 * Registers a Backend Module
	 */
	Tx_Extbase_Utility_Extension::registerModule(
		'sf_oauth',
		// Make module a submodule of 'web'
		'tools',
		// Submodule key
		'tx_sfoauth_m1',
		// Position
		'',
		// An array holding the controller-action-combinations that are accessible
		array(
			'Account' => 'index, new, create, delete, edit, update, authorize, accepted',
			'Consumer' => 'index, show, new, create, delete, edit, update',
		),
		array(
			'access' => 'admin',
			'icon'   => 'EXT:sf_oauth/ext_icon.gif',
			'labels' => 'LLL:EXT:sf_oauth/Resources/Private/Language/locallang_mod.xml',
		)
	);

	/**
	 * Add labels for context sensitive help (CSH)
	 */
	t3lib_extMgm::addLLrefForTCAdescr(
		'_MOD_tools_SfOauthTxSfoauthM1',
		'EXT:sf_oauth/Resources/Private/Language/locallang_csh.xml'
	);
}

?>
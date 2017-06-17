<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(function () {
    if (TYPO3_MODE === 'BE' && !(TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_INSTALL)) {
        /**
         * Registers a Backend Module
         */
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
            'sf_oauth',
            'tools',
            'tx_sfoauth_m1',
            '',
            [
                'Account' => 'index, new, create, delete, edit, update, authorize, accepted',
                'Consumer' => 'index, show, new, create, delete, edit, update',
            ],
            [
                'access' => 'admin',
                'icon' => 'EXT:sf_oauth/ext_icon.gif',
                'labels' => 'LLL:EXT:sf_oauth/Resources/Private/Language/locallang_mod.xml',
            ]
        );

        /**
         * Add labels for context sensitive help (CSH)
         */
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
            '_MOD_tools_SfOauthTxSfoauthM1',
            'EXT:sf_oauth/Resources/Private/Language/locallang_csh.xml'
        );
    }
});

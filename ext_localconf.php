<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        'sf_oauth',
        'oauthConsumer',
        'tx_sf_oauth_consumer',
        [
            'title' => 'Oauth Consumer',
            'description' => 'Offers the possibility to authenticate and communicate with Oauth Server (eg Twitter)',
            'subtype' => '',
            'available' => true,
            'priority' => 60,
            'quality' => 70,
            'os' => '',
            'exec' => '',
            'classFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('sf_oauth') .
                'Classes/Service/OauthConsumer.php',
            'className' => 'Tx_SfOauth_Service_OauthConsumer',
        ]
    );
});

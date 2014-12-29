<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}



t3lib_extMgm::addService(
	'sf_oauth',
	'oauthConsumer' /* sv type */,
	'tx_sf_oauth_consumer' /* sv key */,
	array(
		'title' => 'Oauth Consumer',
		'description' => 'Offers the possibilty to authenticate and communicate with Oauth Server (eg Twitter)',
		'subtype' => '',
		'available' => TRUE,
		'priority' => 60,
		'quality' => 70,
		'os' => '',
		'exec' => '',
		'classFile' => t3lib_extMgm::extPath('sf_oauth') .
			'Classes/Service/OauthConsumer.php',
		'className' => 'Tx_SfOauth_Service_OauthConsumer',
	)
);

?>
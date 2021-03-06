<?php

########################################################################
# Extension Manager/Repository config file for ext "sf_oauth".
#
# Auto generated 17-10-2010 18:37
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'OAuth Service',
	'description' => 'Offers a service that can be instantiated in frontend
		plugins and backend moduls to authenticate against twitter to post
		upates or get informations and tweets.',
	'category' => 'Sebastian Fischer',
	'shy' => 0,
	'version' => '0.1.8',
	'dependencies' => 'extbase,fluid',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 1,
	'lockType' => '',
	'author' => 'Sebastian Fischer',
	'author_email' => 'typo3@evoweb.de',
	'author_company' => 'evoweb',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.3.0-0.0.0',
			'extbase' => '1.2.0-',
			'fluid' => '1.2.0-',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
	'_md5_values_when_last_written' => 'a:32:{s:12:"ext_icon.gif";s:4:"8c88";s:17:"ext_localconf.php";s:4:"cb4f";s:14:"ext_tables.php";s:4:"dd67";s:24:"ext_typoscript_setup.txt";s:4:"44e7";s:40:"Classes/Controller/AccountController.php";s:4:"aa94";s:41:"Classes/Controller/ConsumerController.php";s:4:"9d3c";s:32:"Classes/Domain/Model/Account.php";s:4:"77ae";s:33:"Classes/Domain/Model/Consumer.php";s:4:"981c";s:48:"Classes/Domain/Repository/AbstractRepository.php";s:4:"a1f0";s:47:"Classes/Domain/Repository/AccountRepository.php";s:4:"da4d";s:48:"Classes/Domain/Repository/ConsumerRepository.php";s:4:"376d";s:35:"Classes/Service/OauthConnection.php";s:4:"39b5";s:33:"Classes/Service/OauthConsumer.php";s:4:"42fd";s:33:"Classes/Service/OauthResponse.php";s:4:"ade9";s:54:"Classes/ViewHelpers/Be/Buttons/CshInlineViewHelper.php";s:4:"519e";s:49:"Classes/ViewHelpers/Be/Buttons/IconViewHelper.php";s:4:"0a06";s:46:"Resources/Private/Backend/Layouts/default.html";s:4:"63af";s:57:"Resources/Private/Backend/Templates/Account/Accepted.html";s:4:"eafa";s:58:"Resources/Private/Backend/Templates/Account/Authorize.html";s:4:"049c";s:53:"Resources/Private/Backend/Templates/Account/Edit.html";s:4:"bc0f";s:54:"Resources/Private/Backend/Templates/Account/Index.html";s:4:"db8b";s:52:"Resources/Private/Backend/Templates/Account/New.html";s:4:"f617";s:54:"Resources/Private/Backend/Templates/Consumer/Edit.html";s:4:"b8d3";s:55:"Resources/Private/Backend/Templates/Consumer/Index.html";s:4:"335e";s:53:"Resources/Private/Backend/Templates/Consumer/New.html";s:4:"c608";s:40:"Resources/Private/Language/locallang.xml";s:4:"de0e";s:43:"Resources/Private/Language/locallang_be.xml";s:4:"7d53";s:44:"Resources/Private/Language/locallang_csh.xml";s:4:"de15";s:44:"Resources/Private/Language/locallang_mod.xml";s:4:"8950";s:42:"Resources/Private/Partials/formErrors.html";s:4:"cc71";s:45:"Resources/Public/JavaScript/extjs-lightbox.js";s:4:"1a67";s:14:"doc/manual.sxw";s:4:"e57b";}',
);

?>
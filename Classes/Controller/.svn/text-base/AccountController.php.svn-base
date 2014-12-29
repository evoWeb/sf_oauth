<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2010 Sebastian Fischer <typo3@evoweb.de>
 *
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Account controller
 *
 * @author		Sebastian Fischer <typo3@evoweb.de>
 * @package		sf_oauth
 * @subpackage	AccountController
 */
class Tx_SfOauth_Controller_AccountController extends Tx_Extbase_MVC_Controller_ActionController {
	/**
	 * @var Tx_SfOauth_Domain_Repository_AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @var Tx_SfOauth_Domain_Repository_ConsumerRepository
	 */
	protected $consumerRepository;

	/**
	 * Initialize all actions
	 *
	 * @return	void
	 */
	protected function initializeAction() {
		$this->accountRepository = t3lib_div::makeInstance('Tx_SfOauth_Domain_Repository_AccountRepository');
		$this->consumerRepository = t3lib_div::makeInstance('Tx_SfOauth_Domain_Repository_ConsumerRepository');
	}

	/**
	 * Indexes all accounts
	 *
	 * @return	void
	 */
	public function indexAction() {
		$this->view->assign('accounts', $this->accountRepository->findAll());
	}

	/**
	 * Displays a form for creating a new account
	 *
	 * @param	Tx_SfOauth_Domain_Model_Account	$newAccount	form information object
	 * @return	string	An HTML form for creating a new account
	 * @dontvalidate $newAccount
	 */
	public function newAction(Tx_SfOauth_Domain_Model_Account $newAccount = NULL) {
		$this->view->assign('newAccount', $newAccount);

		$consumers = $this->consumerRepository->findAll();
		$this->view->assign('consumers', $consumers);
	}

	/**
	 * Creates a new consumer
	 *
	 * @param	Tx_SfOauth_Domain_Model_Account	$newAccount	information for account
	 * @return	void
	 */
	public function createAction(Tx_SfOauth_Domain_Model_Account $newAccount) {
		$this->accountRepository->add($newAccount);
		$this->flashMessages->add('Your new account was created.');
		$this->redirect('index');
	}

	/**
	 * Deletes an existing account
	 *
	 * @return	void
	 */
	public function deleteAction() {
		if ($this->request->hasArgument('account')) {
			$uid = (int) $this->request->getArgument('account');
		}

		$this->accountRepository->remove($uid);
		$this->flashMessages->add('Your account has been removed.');
		$this->redirect('index');
	}

	/**
	 * Edits an existing account
	 *
	 * @return	string	Form for editing the existing account
	 * @dontvalidate $account
	 */
	public function editAction() {
		if ($this->request->hasArgument('account')) {
			$key = (int) $this->request->getArgument('account');
		}

		$account = $this->accountRepository->findByUid($key);
		$this->view->assign('account', $account);
	}

	/**
	 * Updates an existing account
	 *
	 * @return	void
	 */
	public function updateAction() {
		if ($this->request->hasArgument('editAccount')) {
			$accountValues = $this->request->getArgument('editAccount');

			$account = $this->accountRepository->findByUid($accountValues['__identity']);
			unset($accountValues['__identity']);

			$this->propertyMapper->map(array_keys($accountValues), $accountValues, $account);
		}

		$this->accountRepository->update($account);
		$this->flashMessages->add('Your account has been updated.');
		$this->redirect('index');
	}

	/**
	 * Authorize agains oauth service provider
	 *
	 * @return	string An HTML form for creating a new account
	 */
	public function authorizeAction() {
		if ($this->request->hasArgument('account')) {
			$key = (int) $this->request->getArgument('account');
		}

		$oauthService = t3lib_div::makeInstanceService('tx_sf_oauth_consumer');
		$oauthService->setAccount($key);

		$callbackUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/typo3/mod.php?' .
			'M=tools_SfOauthTxSfoauthM1&' .
			'tx_sfoauth_tools_sfoauthtxsfoauthm1[controller]=Account&' .
			'tx_sfoauth_tools_sfoauthtxsfoauthm1[action]=accepted&' .
			'tx_sfoauth_tools_sfoauthtxsfoauthm1[account]=' . $key;
		$response = $oauthService->getRequestToken($callbackUrl);

		if ($response->code == 200) {
			$this->view->assign('authorizationUrl', $oauthService->getAuthenticationUrl($response->oauth_token));
		} else {
			$this->flashMessages->add('Twitter was not accessible or responded in an unexpected way. [' . $response->code . ']');
			$this->redirect('index');
		}
	}

	/**
	 * Our request was accepted and this is the resulting message
	 *
	 * @return	string An HTML form for creating a new account
	 */
	public function acceptedAction() {
		if ($this->request->hasArgument('account')) {
			$key = (int) $this->request->getArgument('account');
		}

		$oauthService = t3lib_div::makeInstanceService('tx_sf_oauth_consumer');
		$oauthService->setAccount($key);
		$oauthService->setToken($_GET['oauth_token']);

		$response = $oauthService->getAccessToken($_GET['oauth_verifier']);

		if ($response->code == 200) {
			$this->accountRepository = t3lib_div::makeInstance('Tx_SfOauth_Domain_Repository_AccountRepository');

			$account = $this->accountRepository->findByUid($key);
			$account->setConsumerToken($response->oauth_token);
			$account->setConsumerSecret($response->oauth_token_secret);
			$account->setStatus(0);

			$this->accountRepository->update($account);
		}
	}
}

?>
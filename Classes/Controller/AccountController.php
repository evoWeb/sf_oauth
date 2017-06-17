<?php
namespace Evoweb\SfOauth\Controller;

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

use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Account controller
 */
class AccountController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var \Evoweb\SfOauth\Domain\Repository\AccountRepository
     */
    protected $accountRepository;

    /**
     * @var \Evoweb\SfOauth\Domain\Repository\ConsumerRepository
     */
    protected $consumerRepository;

    /**
     * Initialize all actions
     *
     * @return void
     */
    protected function initializeAction()
    {
        $this->accountRepository = $this->objectManager->get(
            \Evoweb\SfOauth\Domain\Repository\AccountRepository::class
        );
        $this->consumerRepository = $this->objectManager->get(
            \Evoweb\SfOauth\Domain\Repository\ConsumerRepository::class
        );
    }

    /**
     * Indexes all accounts
     *
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign('accounts', $this->accountRepository->findAll());
    }

    /**
     * Displays a form for creating a new account
     *
     * @param \Evoweb\SfOauth\Domain\Model\Account $newAccount form information object
     *
     * @return void An HTML form for creating a new account
     *
     * @ignorevalidation $newAccount
     */
    public function newAction(\Evoweb\SfOauth\Domain\Model\Account $newAccount = null)
    {
        $this->view->assign('newAccount', $newAccount);

        $consumers = $this->consumerRepository->findAll();

        $this->view->assign('consumers', $consumers);
    }

    /**
     * Creates a new consumer
     *
     * @param \Evoweb\SfOauth\Domain\Model\Account $newAccount information for account
     *
     * @return void
     */
    public function createAction(\Evoweb\SfOauth\Domain\Model\Account $newAccount)
    {
        $this->accountRepository->add($newAccount);

        $this->addFlashMessage(
            'Your new account was created.',
            '',
            FlashMessage::INFO
        );

        $this->redirect('index');
    }

    /**
     * Deletes an existing account
     *
     * @param \Evoweb\SfOauth\Domain\Model\Account $account
     *
     * @return void
     */
    public function deleteAction(\Evoweb\SfOauth\Domain\Model\Account $account)
    {
        $this->accountRepository->remove($account->getUid());

        $this->addFlashMessage(
            'Your account has been removed.',
            '',
            FlashMessage::INFO
        );

        $this->redirect('index');
    }

    /**
     * Edits an existing account
     *
     * @param \Evoweb\SfOauth\Domain\Model\Account $account
     *
     * @return void Form for editing the existing account
     */
    public function editAction(\Evoweb\SfOauth\Domain\Model\Account $account)
    {
        $this->view->assign('account', $account);
    }

    /**
     * Updates an existing account
     *
     * @param \Evoweb\SfOauth\Domain\Model\Account $account
     *
     * @return void
     */
    public function updateAction(\Evoweb\SfOauth\Domain\Model\Account $account)
    {
        $this->accountRepository->update($account);

        $this->addFlashMessage(
            'Your account has been updated.',
            '',
            FlashMessage::INFO
        );

        $this->redirect('index');
    }

    /**
     * Authorize against oauth service provider
     *
     * @return void An HTML form for creating a new account
     */
    public function authorizeAction()
    {
        $key = '';
        if ($this->request->hasArgument('account')) {
            $key = (int)$this->request->getArgument('account');
        }

        $oauthService = GeneralUtility::makeInstanceService('tx_sf_oauth_consumer');
        $oauthService->setAccount($key);

        $callbackUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/typo3/mod.php?' .
            'M=tools_SfOauthTxSfoauthM1&' .
            'tx_sfoauth_tools_sfoauthtxsfoauthm1[controller]=Account&' .
            'tx_sfoauth_tools_sfoauthtxsfoauthm1[action]=accepted&' . 'tx_sfoauth_tools_sfoauthtxsfoauthm1[account]=' .
            $key;
        $response = $oauthService->getRequestToken($callbackUrl);

        if ($response->code == 200) {
            $this->view->assign('authorizationUrl', $oauthService->getAuthenticationUrl($response->oauth_token));
        } else {
            $this->addFlashMessage(
                'Twitter was not accessible or responded in an unexpected way. [' . $response->code . ']',
                '',
                FlashMessage::ERROR
            );

            $this->redirect('index');
        }
    }

    /**
     * Our request was accepted and this is the resulting message
     *
     * @return void An HTML form for creating a new account
     */
    public function acceptedAction()
    {
        $key = '';
        if ($this->request->hasArgument('account')) {
            $key = (int)$this->request->getArgument('account');
        }

        $oauthService = GeneralUtility::makeInstanceService('tx_sf_oauth_consumer');
        $oauthService->setAccount($key);
        $oauthService->setToken($_GET['oauth_token']);

        $response = $oauthService->getAccessToken($_GET['oauth_verifier']);

        if ($response->code == 200) {
            $account = $this->accountRepository->findByUid($key);
            $account->setConsumerToken($response->oauth_token);
            $account->setConsumerSecret($response->oauth_token_secret);
            $account->setStatus(0);

            $this->accountRepository->update($account);
        }
    }
}

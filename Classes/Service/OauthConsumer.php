<?php
namespace Evoweb\SfOauth\Service;

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
 * Consumer of the oauth connection
 */
class OauthConsumer extends \TYPO3\CMS\Core\Service\AbstractService
{
    /**
     * Same as class name
     *
     * @var string
     */
    public $prefixId = 'tx_sf_oauth_consumer';

    /**
     * Path to this script relative to the extension dir.
     *
     * @var string
     */
    public $scriptRelPath = 'Classes/Service/OauthConsumer.php';

    /**
     * The extension key.
     *
     * @var string
     */
    public $extKey = 'sf_oauth';

    /**
     * @var OauthConnection
     */
    protected $connection;

    /**
     * @var \Evoweb\SfOauth\Domain\Repository\AccountRepository
     */
    protected $accountRepository;

    /**
     * @var \Evoweb\SfOauth\Domain\Model\Account
     */
    protected $account;

    /**
     * @var string
     */
    protected $token = null;

    /**
     * @var string
     */
    protected $tokenSecret = null;

    /**
     * Initialize the service
     *
     * @return boolean
     */
    public function init()
    {
        $available = parent::init();

        $this->connection = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(OauthConnection::class);

        return $available;
    }

    /**
     * performs the service processing
     *
     * @param string $content Content which should be processed.
     * @param string $type Content type
     * @param array $conf Configuration array
     *
     * @return boolean
     */
    public function process($content = '', $type = '', $conf = array())
    {
        // Depending on the service type there's not a process() function.
        // You have to implement the API of that service type.
        return true;
    }

    /**
     * Setter for uid
     *
     * @param integer $uid id of the account to use
     *
     * @return void
     */
    public function setAccount($uid)
    {
        $this->accountRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \Evoweb\SfOauth\Domain\Repository\AccountRepository::class
        );
        $this->account = $this->accountRepository->findByUid($uid);
        $this->connection->setAccount($this->account);
    }

    /**
     * Setter for token and token_secret
     *
     * @param string $token token needed to auth
     * @param string $tokenSecret token secret needed to auth
     *
     * @return void
     */
    public function setToken($token = null, $tokenSecret = null)
    {
        if (!is_null($token)) {
            $this->token = $token;
        }

        if (!is_null($tokenSecret)) {
            $this->tokenSecret = $tokenSecret;
        }
    }

    /**
     * Returns a combination of authorization url and oauth_token
     *
     * @param string $token token needed to get an authorization url
     *
     * @return string
     */
    public function getAuthorizationUrl($token)
    {
        return $this->account->getConsumer()->getAuthorizeUrl() . '?oauth_token=' . $token;
    }

    /**
     * Returns a combination of authentication url and oauth_token
     *
     * @param string $token token needed to get an authentication url
     *
     * @return string
     */
    public function getAuthenticationUrl($token)
    {
        return $this->account->getConsumer()->getAuthenticateUrl() . '?oauth_token=' . $token;
    }

    /**
     * Get a request token of an oauth service provider
     *
     * @param string $callbackUrl url to use as callback
     *
     * @return OauthResponse
     */
    public function getRequestToken($callbackUrl = '')
    {
        $oauth = [];

        if ($callbackUrl != '') {
            $oauth['oauth_callback'] = $callbackUrl;
        }

        return $this->connection->httpRequest(
            'GET',
            $this->account->getConsumer()->getRequestUrl(),
            [
                'oauth' => $oauth,
            ]
        );
    }

    /**
     * Request oauth service provider for authentication token with request
     * token given
     *
     * @param string $token token to get an authentication token set
     *
     * @return OauthResponse
     */
    public function getAuthenticationToken($token)
    {
        return $this->connection->httpRequest('GET', $this->getAuthenticationUrl($token));
    }

    /**
     * Get the access token, that is needed in further usage
     *
     * @param string $verifier verifier served from oauth to confirm the callback
     *
     * @return OauthResponse
     */
    public function getAccessToken($verifier)
    {
        $oauth = [];

        if (!is_null($this->token)) {
            $oauth['oauth_token'] = $this->token;
            $oauth['oauth_verifier'] = $verifier;
        }

        return $this->connection->httpRequest(
            'GET',
            $this->account->getConsumer()->getAccessUrl(),
            [
                'oauth' => $oauth,
            ]
        );
    }

    /**
     * Magic method to handle all calls that are not returned by a available method
     *
     * @param string $name name of the method to call
     * @param array $params parameters to use by method call
     *
     * @return string
     */
    public function __call($name, $params = null)
    {
        $parts = explode('_', $name);
        $method = strtoupper(array_shift($parts));
        $parts = implode('_', $parts);

        $separator = (substr($this->account->getConsumer()->getApiUrl(), -1) == '/' ? '' : '/');
        // $query = preg_replace('/[A-Z]|[0-9]+/e', "'/'.strtolower('\\0')", $parts) . '.json';
        $query = preg_replace_callback(
            '/[A-Z]|[0-9]+/',
            function ($matches) {
                return '\'/\'' . strtolower($matches[0]);
            },
            $parts
        );

        $args = [];
        if (!empty($params)) {
            $args = (array)array_shift($params);
        }

        return $this->connection->httpRequest(
            $method,
            $this->account->getConsumer()->getApiUrl() . $separator . $query . '.json',
            [
                'request' => $args,
            ]
        );
    }
}

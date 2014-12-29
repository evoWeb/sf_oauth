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
 * Connection via curl. This object handels the requests
 *
 * @author		Sebastian Fischer <typo3@evoweb.de>
 * @package		sf_oauth
 * @subpackage	OauthConnection
 */
class Tx_SfOauth_Service_OauthConnection {
	/**
	 * @var	string
	 */
	const version = '1.0';

	/**
	 * @var	Tx_SfOauth_Domain_Model_Account
	 */
	protected $account;

	/**
	 * Setter for account
	 *
	 * @param	Tx_SfOauth_Domain_Model_Account	$account	account for request
	 * @return	void
	 */
	public function setAccount($account) {
		$this->account = $account;
	}

	/**
	 * Request the service Provider
	 *
	 * @param	string	$method	POST or GET
	 * @param	string	$url	url to call
	 * @param	array	$params	parameter to transmit
	 * @return	Tx_SfOauth_Service_OauthResponse
	 */
	public function httpRequest($method = NULL, $url = NULL, $params = NULL) {
		$result = NULL;

		if (empty($method) OR empty($url)) {
			$result = FALSE;
		} else {
			// if (strpos($url, 'https://') === false) {
			$this->signatureMethod = 'HMAC-SHA1';
			// } else {
			// $this->signatureMethod = 'PLAINTEXT';
			// }

			if (empty($params['oauth_signature'])) {
				$params = $this->prepareParameters($method, $url, $params);
			}

			switch (strtolower($method)) {
				case 'get':
					$result = $this->httpGet($url, $params);
					break;
				case 'post':
				default:
					$result = $this->httpPost($url, $params);
					break;
			}
		}

		return $result;
	}

	/**
	 * Calls oauth service provider with get parameters
	 *
	 * @param	string	$url	url to call
	 * @param	array	$params	parameters to transmit
	 * @return	Tx_SfOauth_Service_OauthResponse
	 */
	protected function httpGet($url, $params = NULL) {
		if (count($params['request']) > 0) {
			$paramParts = array();
			foreach ($params['request'] as $key => $value) {
				$paramParts[] = $key . '=' . $value;
			}
			$url .= '?' . implode('&', $paramParts);
		}

		$curlHandler = curl_init($url);
		$this->addOauthHeaders($curlHandler, $url, $params['oauth']);
		curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, TRUE);

		return t3lib_div::makeInstance('Tx_SfOauth_Service_OauthResponse', $curlHandler);
	}

	/**
	 * Calls oauth service provider with post parameters
	 *
	 * @param	string	$url	url to call
	 * @param	array	$params	parameters to transmit
	 * @return	Tx_SfOauth_Service_OauthResponse
	 */
	protected function httpPost($url, $params = NULL) {
		$curlHandler = curl_init($url);
		$this->addOauthHeaders($curlHandler, $url, $params['oauth']);
		curl_setopt($curlHandler, CURLOPT_POST, 1);
		curl_setopt($curlHandler, CURLOPT_POSTFIELDS, http_build_query($params['request']));
		curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, TRUE);

		return t3lib_div::makeInstance('Tx_SfOauth_Service_OauthResponse', $curlHandler);
	}

	/**
	 * Addes header information to curlHandler and returns it
	 *
	 * @param	curl_resource	&$curlHandler	curl handler by reference
	 * @param	string	$url	url to call
	 * @param	array	$oauthHeaders	header data to set
	 * @return	void
	 */
	protected function addOauthHeaders(&$curlHandler, $url, $oauthHeaders) {
		$urlParts = parse_url($url);
		$oauth = 'Authorization: OAuth realm="' . $urlParts['path'] . '", ';

		$headerParts = array();
		foreach ($oauthHeaders as $key => $value) {
			$headerParts[] =  $this->encode($key) . '="' . $this->encode($value) . '"';
		}
		$oauth .= implode(', ', $headerParts);

		$header = array('Expect:', $oauth);

		curl_setopt($curlHandler, CURLOPT_HTTPHEADER, $header);
	}

	/**
	 * Gather information for the request params
	 *
	 * @param	string	$method	POST or GET
	 * @param	string	$url	url to call
	 * @param	array	$params	parameters to set
	 * @return	array
	 */
	protected function prepareParameters($method = NULL, $url = NULL, $params = NULL) {
		if (empty($method) OR empty($url)) {
			return FALSE;
		}

		$request = (array) $params['request'];

		$oauth = $params['oauth'];
		$oauth['oauth_consumer_key'] = $this->account->getConsumer()->getKey();
		$oauth['oauth_nonce'] = $this->getNonce();
		$oauth['oauth_timestamp'] = $this->getTimestamp();
		$oauth['oauth_signature_method'] = $this->signatureMethod;
		$oauth['oauth_version'] = self::version;

		if (!$oauth['oauth_token'] AND $this->account->getConsumerToken()) {
			$oauth['oauth_token'] = $this->account->getConsumerToken();
		}

		$encodedParams = array_merge($oauth, $request);
		ksort($encodedParams);

			// signing
		$oauth['oauth_signature'] = $this->generateSignature($method, $url, $encodedParams);

		return array('request' => $request, 'oauth' => $oauth);
	}

	/**
	 * Generate the signature for the request
	 *
	 * @param	string	$method	PUT or GET
	 * @param	string	$url	url to call
	 * @param	array	$params	parameters to transmit
	 * @return	string
	 */
	protected function generateSignature($method = NULL, $url = NULL, $params = NULL) {
		if (empty($method) OR empty($url)) {
			return FALSE;
		}

			// concatenating
		$concatenatedParams = array();
		foreach ($params as $key => $value) {
			$value = $this->encode($value);
			$concatenatedParams[] = $key . '=' . $value;
		}
		$concatenatedParams = $this->encode(implode('&', $concatenatedParams));

			// normalize url
		$normalizedUrl = $this->encode($this->normalizeUrl($url));

		$signatureBaseString = $method . '&' . $normalizedUrl . '&' . $concatenatedParams;
		return $this->signString($signatureBaseString);
	}

	/**
	 * Sign the url string if signing method HMAC-SHA1 is used
	 *
	 * @param	string	$baseString	basic string to encode
	 * @return	string
	 */
	protected function signString($baseString = NULL) {
		$signedUrl = FALSE;

		switch ($this->signatureMethod) {
			case 'HMAC-SHA1':
			default:
				$key = implode('&', array(
					$this->encode($this->account->getConsumer()->getSecret()),
					$this->account->getConsumerSecret() ?
						$this->encode($this->account->getConsumerSecret()) :
						''
				));

				$signedUrl = base64_encode(hash_hmac('sha1', $baseString, $key, TRUE));
				break;
		}

		return $signedUrl;
	}

	/**
	 * Normalize the request url
	 *
	 * @param	string	$url	url to normalize
	 * @return	string
	 */
	protected function normalizeUrl($url = NULL) {
		$urlParts = parse_url($url);
		$scheme = strtolower($urlParts['scheme']);
		$host = strtolower($urlParts['host']);
		$port = isset($urlParts['port']) ? intval($urlParts['port']) : 0;

		$normalizedUrl = $scheme . '://' . $host;

		if ($port > 0 AND (
					($scheme === 'http' AND $port !== 80) OR
					($scheme === 'https' AND $port !== 443)
				)) {
			$normalizedUrl .= ':' . $port;
		}

		$normalizedUrl .= $urlParts['path'];

		if (!empty($urlParts['query'])) {
			$normalizedUrl .= '?' . $urlParts['query'];
		}

		return $normalizedUrl;
	}

	/**
	 * utf8_encodes the string and returns the rawurlencodes result
	 *
	 * @param	string	$string	string to encode
	 * @return	string
	 */
	public function encode($string) {
		return rawurlencode(utf8_encode($string));
	}

	/**
	 * Generate a uniqid based on random value and return the md5 hashed result
	 *
	 * @return	string
	 */
	protected function getNonce() {
		return md5(uniqid(rand(), TRUE));
	}

	/**
	 * Returns the exec_time of TYPO3
	 *
	 * @return	integer
	 */
	protected function getTimestamp() {
		return $GLOBALS['EXEC_TIME'];
	}
}

?>
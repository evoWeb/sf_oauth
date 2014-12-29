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
 * Consumer
 *
 * @author		Sebastian Fischer <typo3@evoweb.de>
 * @package		sf_oauth
 * @subpackage	Consumer
 */
class Tx_SfOauth_Domain_Model_Consumer extends Tx_Extbase_DomainObject_AbstractEntity {
	/**
	 * @var	string
	 */
	protected $title;

	/**
	 * @var	string
	 */
	protected $key;

	/**
	 * @var	string
	 */
	protected $secret;

	/**
	 * @var	string
	 */
	protected $requestUrl;

	/**
	 * @var	string
	 */
	protected $accessUrl;

	/**
	 * @var	string
	 */
	protected $authorizeUrl;

	/**
	 * @var	string
	 */
	protected $authenticateUrl;

	/**
	 * @var	string
	 */
	protected $apiUrl;

	/**
	 * The generic constructor. If you want to implement your own __constructor()
	 * method in your Domain Object you have to call $this->initializeObject()
	 * in the first line of your constructor.
	 *
	 * @param	integer	$uid	uid of the model
	 * @return	void
	 */
	public function __construct($uid) {
		$this->uid = (int) $uid;
		$this->initializeObject();
	}

	/**
	 * Getter for title
	 *
	 * @return	string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Setter for title
	 *
	 * @param	string	$title	title to set
	 * @return	void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Getter for key
	 *
	 * @return	string
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * Setter for key
	 *
	 * @param	string	$key	key to set
	 * @return	void
	 */
	public function setKey($key) {
		$this->key = $key;
	}

	/**
	 * Getter for secret
	 *
	 * @return	string
	 */
	public function getSecret() {
		return $this->secret;
	}

	/**
	 * Setter for secret
	 *
	 * @param	string	$secret	secret to set
	 * @return	void
	 */
	public function setSecret($secret) {
		$this->secret = $secret;
	}

	/**
	 * Getter for requestUrl
	 *
	 * @return	string
	 */
	public function getRequestUrl() {
		return $this->requestUrl;
	}

	/**
	 * Setter for requestUrl
	 *
	 * @param	string	$requestUrl	request url to set
	 * @return	void
	 */
	public function setRequestUrl($requestUrl) {
		$this->requestUrl = $requestUrl;
	}

	/**
	 * Getter for accessUrl
	 *
	 * @return	string
	 */
	public function getAccessUrl() {
		return $this->accessUrl;
	}

	/**
	 * Setter for accessUrl
	 *
	 * @param	string	$accessUrl	access url to set
	 * @return	void
	 */
	public function setAccessUrl($accessUrl) {
		$this->accessUrl = $accessUrl;
	}

	/**
	 * Getter for authorizeUrl
	 *
	 * @return	string
	 */
	public function getAuthorizeUrl() {
		return $this->authorizeUrl;
	}

	/**
	 * Setter for authorizeUrl
	 *
	 * @param	string	$authorizeUrl	authorize url to set
	 * @return	void
	 */
	public function setAuthorizeUrl($authorizeUrl) {
		$this->authorizeUrl = $authorizeUrl;
	}

	/**
	 * Getter for authenticateUrl
	 *
	 * @return	string
	 */
	public function getAuthenticateUrl() {
		return $this->authenticateUrl;
	}

	/**
	 * Setter for authenticateUrl
	 *
	 * @param	string	$authenticateUrl	authenticate url to set
	 * @return	void
	 */
	public function setAuthenticateUrl($authenticateUrl) {
		$this->authenticateUrl = $authenticateUrl;
	}

	/**
	 * Getter for apiUrl
	 *
	 * @return	string
	 */
	public function getApiUrl() {
		return $this->apiUrl;
	}

	/**
	 * Setter for apiUrl
	 *
	 * @param	string	$apiUrl	api url to set
	 * @return	void
	 */
	public function setApiUrl($apiUrl) {
		$this->apiUrl = $apiUrl;
	}
}

?>
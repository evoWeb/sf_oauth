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
 * Account
 *
 * @author		Sebastian Fischer <typo3@evoweb.de>
 * @package		sf_oauth
 * @subpackage	Account
 */
class Tx_SfOauth_Domain_Model_Account extends Tx_Extbase_DomainObject_AbstractEntity {
	/**
	 * @var	string
	 */
	protected $title;

	/**
	 * @var	integer
	 */
	protected $consumer;

	/**
	 * @var	Tx_SfOauth_Domain_Repository_ConsumerRepository
	 */
	protected $consumerObject;

	/**
	 * @var	string
	 */
	protected $consumerToken;

	/**
	 * @var	string
	 */
	protected $consumerSecret;

	/**
	 * @var	boolean
	 */
	protected $status;

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
	 * Getter for consumer
	 *
	 * @return	integer
	 */
	public function getConsumer() {
		if ($this->consumerObject === NULL) {
			$this->consumerObject = t3lib_div::makeInstance('Tx_SfOauth_Domain_Repository_ConsumerRepository')
				->findByUid($this->consumer);
		}

		return $this->consumerObject;
	}

	/**
	 * Setter for consumer
	 *
	 * @param	integer	$consumer	consumer to set
	 * @return	void
	 */
	public function setConsumer($consumer) {
		$this->consumer = $consumer;
	}

	/**
	 * Getter for consumerToken
	 *
	 * @return	string
	 */
	public function getConsumerToken() {
		return $this->consumerToken;
	}

	/**
	 * Setter for consumerToken
	 *
	 * @param	string	$consumerToken	consumer token to set
	 * @return	void
	 */
	public function setConsumerToken($consumerToken) {
		$this->consumerToken = $consumerToken;
	}

	/**
	 * Getter for consumerSecret
	 *
	 * @return	string
	 */
	public function getConsumerSecret() {
		return $this->consumerSecret;
	}

	/**
	 * Setter for consumerSecret
	 *
	 * @param	string	$consumerSecret	consumer secret to set
	 * @return	void
	 */
	public function setConsumerSecret($consumerSecret) {
		$this->consumerSecret = $consumerSecret;
	}

	/**
	 * Setter for status
	 *
	 * @param	integer	$status	status to set
	 * @return	void
	 */
	public function setStatus($status) {
		$this->status = (int) $status;
	}

	/**
	 * Check wether token is set and secure is unset then an error happend
	 *
	 * @return boolean
	 */
	public function getHasError() {
		return ($this->status > 0) ? TRUE : FALSE;
	}

	/**
	 * Checks if no error is present
	 *
	 * @return boolean
	 */
	public function getIsOk() {
		return ($this->status == 0) ? TRUE : FALSE;
	}

	/**
	 * Checks wether authentication agains oauth service is needed
	 *
	 * @return	boolean
	 */
	public function getHasWarning() {
		return ($this->status == -1) ? TRUE : FALSE;
	}
}

?>
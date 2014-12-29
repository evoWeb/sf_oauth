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
 * Response encapsulation
 *
 * @author		Sebastian Fischer <typo3@evoweb.de>
 * @package		sf_oauth
 * @subpackage	OauthResult
 */
class Tx_SfOauth_Service_OauthResponse {
	/**
	 * @var	curl resource
	 */
	protected $curlHandler;

	/**
	 * @var	array
	 */
	protected $responses;

	/**
	 * @var	array
	 */
	protected $properties = array(
		'code' => CURLINFO_HTTP_CODE,
		'time' => CURLINFO_TOTAL_TIME,
		'length' => CURLINFO_CONTENT_LENGTH_DOWNLOAD,
		'type' => CURLINFO_CONTENT_TYPE
	);

	/**
	 * Constructor of the class
	 *
	 * @param	curl_resource	$curlHandler	the handler of the curl connection
	 * @return	void
	 */
	public function __construct($curlHandler) {
		$this->curlHandler = $curlHandler;

		$this->responses['data'] = curl_exec($this->curlHandler);

		$this->storeResponses();
	}

	/**
	 * Destructor of the class
	 *
	 * @return	void
	 */
	public function __destruct() {
		curl_close($this->curlHandler);
	}

	/**
	 * Fetch properties from resourcehandler
	 * Evaluates data and set class attributed with values
	 *
	 * @return	void
	 */
	protected function storeResponses() {
		foreach ($this->properties as $key => $const) {
			$this->responses[$key] = curl_getinfo($this->curlHandler, $const);
		}

		parse_str($this->responses['data'], $result);
		foreach ($result as $key => $value) {
			$this->$key = $value;
		}
	}

	/**
	 * Magic getter that gets called if an attribute is not available;
	 *
	 * @param	string	$name	name of the attribute that should returned
	 * @return	mixed
	 */
	public function __get($name) {
		$response = NULL;

		if (array_key_exists($name, $this->responses)) {
			$response = $this->responses[$name];
		}

		return $response;
	}
}

?>
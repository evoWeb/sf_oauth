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
 * Basic repository to handle model persisted in t3_registry
 *
 * @author		Sebastian Fischer <typo3@evoweb.de>
 * @package		sf_oauth
 * @subpackage	Consumer
 */
class Tx_SfOauth_Domain_Repository_AbstractRepository extends Tx_Extbase_Persistence_Repository {
	/**
	 * @var	string
	 */
	protected $namespace;

	/**
	 * @var	t3lib_Registry
	 */
	protected $registry;

	/**
	 * @var	Tx_Extbase_Property_Mapper
	 */
	protected $propertyMapper;

	/**
	 * Constructs a new Repository
	 *
	 * @return	void
	 */
	public function __construct() {
		parent::__construct();

		$this->namespace = strtolower($this->objectType);
		$this->registry = t3lib_div::makeInstance('t3lib_Registry');

		$this->propertyMapper = t3lib_div::makeInstance('Tx_Extbase_Property_Mapper');
		$this->propertyMapper->injectReflectionService(
			t3lib_div::makeInstance('Tx_Extbase_Reflection_Service')
		);
	}

	/**
	 * Add a consumer to the repository
	 *
	 * @param	object	$model	The object to add
	 * @return	void
	 */
	public function add($model) {
		if (!($model instanceof $this->objectType)) {
			parent::add($model);
		}

		$this->setRegistry((int) $this->getNextKey(), (array) $model->_getProperties());
	}

	/**
	 * Add a consumer to the repository
	 *
	 * @param	object	$modifiedObject	The modified object
	 * @return	void
	 */
	public function update($modifiedObject) {
		$this->setRegistry((int) $modifiedObject->getUid(), (array) $modifiedObject->_getProperties());
	}

	/**
	 * Returns all objects of this repository
	 *
	 * @return	array An array of objects, empty if no objects found
	 */
	public function findAll() {
		$storedEntries = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'entry_key',
			'sys_registry',
			'entry_namespace = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr(
				$this->namespace,
				'sys_registry'
			),
			'',
			'entry_key DESC'
		);

		$result = array();
		foreach ($storedEntries as $storedEntry) {
			$result[] = $this->findByUid($storedEntry['entry_key']);
		}

		return $result;
	}

	/**
	 * Finds an object matching the given identifier.
	 *
	 * @param	integer	$key	key that identify the data in t3_registry
	 * @return	object The matching object if found, otherwise NULL
	 */
	public function findByUid($key) {
		$values = $this->registry->get($this->namespace, $key);

		$object = NULL;
		if (is_array($values) AND count($values)) {
			$object = t3lib_div::makeInstance($this->objectType, $key);
			$this->propertyMapper->map(array_keys($values), $values, $object);
		}

		return $object;
	}

	/**
	 * Remove entry by uid
	 *
	 * @param	integer	$key	uid of model to remove
	 * @return	void
	 */
	public function remove($key) {
		$this->registry->remove($this->namespace, $key);
	}

	/**
	 * Write registry entry
	 *
	 * @param	integer	$key	key to identify the model to store
	 * @param	mixed	$values	values of the model
	 * @return	void
	 */
	protected function setRegistry($key, $values) {
		unset($values['uid']);

		$this->registry->set(
			$this->namespace,
			$key,
			$values
		);
	}

	/**
	 * Get the next uid depending on available uid
	 *
	 * @return	integer
	 */
	protected function getNextKey() {
		$storedEntries = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'entry_key',
			'sys_registry',
			'entry_namespace = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr(
				$this->namespace,
				'sys_registry'
			),
			'',
			'entry_key DESC',
			1
		);

		if (count($storedEntries) > 0) {
			$key = $storedEntries[0]['entry_key'];
		} else {
			$key = 0;
		}

		return ++$key;
	}
}

?>
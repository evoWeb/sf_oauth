<?php
namespace Evoweb\SfOauth\Domain\Repository;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Basic repository to handle model persisted in t3_registry
 */
class AbstractRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var \TYPO3\CMS\Core\Registry
     */
    protected $registry;

    /**
     * @var \TYPO3\CMS\Extbase\Property\PropertyMapper
     */
    protected $propertyMapper;

    /**
     * Constructor
     *
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
     */
    public function __construct(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager = null)
    {
        if (is_null($objectManager)) {
            $objectManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        }
        parent::__construct($objectManager);

        $this->namespace = strtolower($this->objectType);
        $this->registry = $this->objectManager->get(\TYPO3\CMS\Core\Registry::class);
        $this->propertyMapper = $this->objectManager->get(\TYPO3\CMS\Extbase\Property\PropertyMapper::class);
    }

    /**
     * Add a consumer to the repository
     *
     * @param object $model The object to add
     *
     * @return void
     */
    public function add($model)
    {
        if (!($model instanceof $this->objectType)) {
            parent::add($model);
        }

        $this->setRegistry((int)$this->getNextKey(), (array)$model->_getProperties());
    }

    /**
     * Add a consumer to the repository
     *
     * @param object $modifiedObject The modified object
     *
     * @return void
     */
    public function update($modifiedObject)
    {
        $this->setRegistry((int)$modifiedObject->getUid(), (array)$modifiedObject->_getProperties());
    }

    /**
     * Returns all objects of this repository
     *
     * @return array An array of objects, empty if no objects found
     */
    public function findAll()
    {
        $queryBuilder = $this->getQueryBuilderForTable('sys_registry');
        $result = $queryBuilder
            ->select('entry_key')
            ->from('sys_registry')
            ->where(
                $queryBuilder->expr()->eq(
                    'entry_namespace',
                    $queryBuilder->createNamedParameter($this->namespace, \PDO::PARAM_STR)
                )
            )
            ->orderBy('entry_key', 'DESC')
            ->execute();

        $records = [];
        while ($storedEntry = $result->fetch()) {
            $records[] = $this->findByUid($storedEntry['entry_key']);
        }

        return $records;
    }

    /**
     * Finds an object matching the given identifier.
     *
     * @param int $uid key that identify the data in t3_registry
     *
     * @return object The matching object if found, otherwise NULL
     */
    public function findByUid($uid)
    {
        $entry = $this->registry->get($this->namespace, $uid);

        $object = null;
        if (is_array($entry) && count($entry)) {
            /** @var \TYPO3\CMS\Extbase\DomainObject\AbstractEntity $object */
            $object = $this->propertyMapper->convert($entry, $this->objectType);
            $object->_setProperty('uid', $uid);
        }

        return $object;
    }

    /**
     * Remove entry by uid
     *
     * @param integer $key uid of model to remove
     *
     * @return void
     */
    public function remove($key)
    {
        $this->registry->remove($this->namespace, $key);
    }

    /**
     * Write registry entry
     *
     * @param integer $key key to identify the model to store
     * @param mixed $values values of the model
     *
     * @return void
     */
    protected function setRegistry($key, $values)
    {
        unset($values['uid']);

        $this->registry->set($this->namespace, $key, $values);
    }

    /**
     * Get the next uid depending on available uid
     *
     * @return integer
     */
    protected function getNextKey()
    {
        $storedEntries = $this->getDatabaseConnection()->exec_SELECTgetRows(
            'entry_key',
            'sys_registry',
            'entry_namespace = ' . $this->getDatabaseConnection()->fullQuoteStr($this->namespace, 'sys_registry'),
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

    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }

    /**
     * @param string $table
     *
     * @return \TYPO3\CMS\Core\Database\Query\QueryBuilder
     */
    protected function getQueryBuilderForTable($table): \TYPO3\CMS\Core\Database\Query\QueryBuilder
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Database\ConnectionPool::class
        )->getQueryBuilderForTable($table);
    }
}

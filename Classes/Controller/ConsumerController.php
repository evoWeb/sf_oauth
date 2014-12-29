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
 * Consumer controller
 *
 * @author		Sebastian Fischer <typo3@evoweb.de>
 * @package		sf_oauth
 * @subpackage	ConsumerController
 */
class Tx_SfOauth_Controller_ConsumerController extends Tx_Extbase_MVC_Controller_ActionController {
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
		$this->consumerRepository = t3lib_div::makeInstance('Tx_SfOauth_Domain_Repository_ConsumerRepository');
	}

	/**
	 * Indexes all consumer
	 *
	 * @return	void
	 */
	public function indexAction() {
		$consumers = $this->consumerRepository->findAll();
		$this->view->assign('consumers', $consumers);
	}

	/**
	 * Action that displays one single consumer
	 *
	 * @return	void
	 */
	public function showAction() {
			// $consumer = $this->consumerRepository->findByUid();
		$this->view->assign('consumer', $consumer);
	}

	/**
	 * Displays a form for creating a new consumer
	 *
	 * @param	Tx_SfOauth_Domain_Model_Consumer	$newConsumer	consumer
	 * @return	void
	 * @dontvalidate $newConsumer
	 */
	public function newAction(Tx_SfOauth_Domain_Model_Consumer $newConsumer = NULL) {
		$this->view->assign('newConsumer', $newConsumer);
	}

	/**
	 * Creates a new consumer
	 *
	 * @param	Tx_SfOauth_Domain_Model_Consumer	$newConsumer	consumer
	 * @return	void
	 */
	public function createAction(Tx_SfOauth_Domain_Model_Consumer $newConsumer) {
		$this->consumerRepository->add($newConsumer);
		$this->flashMessages->add('Your new consumer was created.');
		$this->redirect('index');
	}

	/**
	 * Deletes an existing consumer
	 *
	 * @return	void
	 */
	public function deleteAction() {
		if ($this->request->hasArgument('consumer')) {
			$uid = (int) $this->request->getArgument('consumer');
		}

		$this->consumerRepository->remove($uid);
		$this->flashMessages->add('Your consumer has been removed.');
		$this->redirect('index');
	}

	/**
	 * Edits an existing consumer
	 *
	 * @return	string	Form for editing the existing consumer
	 * @dontvalidate $consumer
	 */
	public function editAction() {
		if ($this->request->hasArgument('consumer')) {
			$key = (int) $this->request->getArgument('consumer');
		}

		$consumer = $this->consumerRepository->findByUid($key);
		$this->view->assign('consumer', $consumer);
	}

	/**
	 * Updates an existing consumer
	 *
	 * @return	void
	 */
	public function updateAction() {
		if ($this->request->hasArgument('consumer')) {
			$consumerValues = $this->request->getArgument('consumer');

			$consumer = $this->consumerRepository->findByUid($consumerValues['__identity']);
			unset($consumerValues['__identity']);

			$this->propertyMapper->map(array_keys($consumerValues), $consumerValues, $consumer);
		}

		$this->consumerRepository->update($consumer);
		$this->flashMessages->add('Your consumer has been updated.');
		$this->redirect('index');
	}

	/**
	 * Override getErrorFlashMessage to present nice flash error messages.
	 *
	 * @return string
	 */
	protected function getErrorFlashMessage() {
		$result = '';

		switch ($this->actionMethodName) {
			case 'updateAction':
				$result = 'Could not update the consumer:';
			case 'createAction':
				$result = 'Could not create the new consumer:';
			default:
				$result = parent::getErrorFlashMessage();
		}

		return $result;
	}
}

?>
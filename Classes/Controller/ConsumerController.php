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

/**
 * Consumer controller
 */
class ConsumerController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
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
        $this->consumerRepository = $this->objectManager->get(
            \Evoweb\SfOauth\Domain\Repository\ConsumerRepository::class
        );
    }

    /**
     * Indexes all consumer
     *
     * @return void
     */
    public function indexAction()
    {
        $consumers = $this->consumerRepository->findAll();
        $this->view->assign('consumers', $consumers);
    }

    /**
     * Action that displays one single consumer
     *
     * @param \Evoweb\SfOauth\Domain\Model\Consumer $consumer
     *
     * @return void
     */
    public function showAction(\Evoweb\SfOauth\Domain\Model\Consumer $consumer)
    {
        $this->view->assign('consumer', $consumer);
    }

    /**
     * Displays a form for creating a new consumer
     *
     * @param \Evoweb\SfOauth\Domain\Model\Consumer $newConsumer consumer
     *
     * @return void
     *
     * @ignorevalidation $newConsumer
     */
    public function newAction(\Evoweb\SfOauth\Domain\Model\Consumer $newConsumer = null)
    {
        $this->view->assign('newConsumer', $newConsumer);
    }

    /**
     * Creates a new consumer
     *
     * @param \Evoweb\SfOauth\Domain\Model\Consumer $newConsumer consumer
     *
     * @return void
     */
    public function createAction(\Evoweb\SfOauth\Domain\Model\Consumer $newConsumer)
    {
        $this->consumerRepository->add($newConsumer);

        $this->addFlashMessage(
            'Your new consumer was created.',
            '',
            FlashMessage::INFO
        );

        $this->redirect('index');
    }

    /**
     * Delete an existing consumer
     *
     * @param \Evoweb\SfOauth\Domain\Model\Consumer $consumer
     *
     * @return void
     */
    public function deleteAction(\Evoweb\SfOauth\Domain\Model\Consumer $consumer)
    {
        $this->consumerRepository->remove($consumer->getUid());

        $this->addFlashMessage(
            'Your consumer has been removed.',
            '',
            FlashMessage::INFO
        );

        $this->redirect('index');
    }

    /**
     * Edits an existing consumer
     *
     * @param \Evoweb\SfOauth\Domain\Model\Consumer $consumer
     *
     * @return void Form for editing the existing consumer
     */
    public function editAction(\Evoweb\SfOauth\Domain\Model\Consumer $consumer)
    {
        $this->view->assign('consumer', $consumer);
    }

    /**
     * Updates an existing consumer
     *
     * @param \Evoweb\SfOauth\Domain\Model\Consumer $consumer
     *
     * @return void
     */
    public function updateAction(\Evoweb\SfOauth\Domain\Model\Consumer $consumer)
    {
        $this->consumerRepository->update($consumer);

        $this->addFlashMessage(
            'Your consumer has been updated.',
            '',
            FlashMessage::INFO
        );

        $this->redirect('index');
    }

    /**
     * Override getErrorFlashMessage to present nice flash error messages.
     *
     * @return string
     */
    protected function getErrorFlashMessage()
    {
        switch ($this->actionMethodName) {
            case 'updateAction':
                $result = 'Could not update the consumer:';
                break;

            case 'createAction':
                $result = 'Could not create the new consumer:';
                break;

            default:
                $result = parent::getErrorFlashMessage();
        }

        return $result;
    }
}

<?php
namespace Webfox\Placements\Controller;
/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 *
 *
 * @package placements
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ApplicationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * applicationRepository
	 *
	 * @var \Webfox\Placements\Domain\Repository\ApplicationRepository
	 * @inject
	 */
	protected $applicationRepository;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$applications = $this->applicationRepository->findAll();
		$this->view->assign('applications', $applications);
	}

	/**
	 * action show
	 *
	 * @param \Webfox\Placements\Domain\Model\Application $application
	 * @return void
	 */
	public function showAction(\Webfox\Placements\Domain\Model\Application $application) {
		$this->view->assign('application', $application);
	}

	/**
	 * action new
	 *
	 * @param \Webfox\Placements\Domain\Model\Application $newApplication
	 * @dontvalidate $newApplication
	 * @return void
	 */
	public function newAction(\Webfox\Placements\Domain\Model\Application $newApplication = NULL) {
		$this->view->assign('newApplication', $newApplication);
	}

	/**
	 * action create
	 *
	 * @param \Webfox\Placements\Domain\Model\Application $newApplication
	 * @return void
	 */
	public function createAction(\Webfox\Placements\Domain\Model\Application $newApplication) {
		$this->applicationRepository->add($newApplication);
		$this->flashMessageContainer->add('Your new Application was created.');
		$this->redirect('list');
	}

	/**
	 * action edit
	 *
	 * @param \Webfox\Placements\Domain\Model\Application $application
	 * @return void
	 */
	public function editAction(\Webfox\Placements\Domain\Model\Application $application) {
		$this->view->assign('application', $application);
	}

	/**
	 * action update
	 *
	 * @param \Webfox\Placements\Domain\Model\Application $application
	 * @return void
	 */
	public function updateAction(\Webfox\Placements\Domain\Model\Application $application) {
		$this->applicationRepository->update($application);
		$this->flashMessageContainer->add('Your Application was updated.');
		$this->redirect('list');
	}

	/**
	 * action delete
	 *
	 * @param \Webfox\Placements\Domain\Model\Application $application
	 * @return void
	 */
	public function deleteAction(\Webfox\Placements\Domain\Model\Application $application) {
		$this->applicationRepository->remove($application);
		$this->flashMessageContainer->add('Your Application was removed.');
		$this->redirect('list');
	}

}

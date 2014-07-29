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
class ResumeController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * resumeRepository
	 *
	 * @var \Webfox\Placements\Domain\Repository\ResumeRepository
	 * @inject
	 */
	protected $resumeRepository;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$resumes = $this->resumeRepository->findAll();
		$this->view->assign('resumes', $resumes);
	}

	/**
	 * action show
	 *
	 * @param \Webfox\Placements\Domain\Model\Resume $resume
	 * @return void
	 */
	public function showAction(\Webfox\Placements\Domain\Model\Resume $resume) {
		$this->view->assign('resume', $resume);
	}

	/**
	 * action new
	 *
	 * @param \Webfox\Placements\Domain\Model\Resume $newResume
	 * @dontvalidate $newResume
	 * @return void
	 */
	public function newAction(\Webfox\Placements\Domain\Model\Resume $newResume = NULL) {
		$this->view->assign('newResume', $newResume);
	}

	/**
	 * action create
	 *
	 * @param \Webfox\Placements\Domain\Model\Resume $newResume
	 * @return void
	 */
	public function createAction(\Webfox\Placements\Domain\Model\Resume $newResume) {
		$this->resumeRepository->add($newResume);
		$this->flashMessageContainer->add('Your new Resume was created.');
		$this->redirect('list');
	}

	/**
	 * action edit
	 *
	 * @param \Webfox\Placements\Domain\Model\Resume $resume
	 * @return void
	 */
	public function editAction(\Webfox\Placements\Domain\Model\Resume $resume) {
		$this->view->assign('resume', $resume);
	}

	/**
	 * action update
	 *
	 * @param \Webfox\Placements\Domain\Model\Resume $resume
	 * @return void
	 */
	public function updateAction(\Webfox\Placements\Domain\Model\Resume $resume) {
		$this->resumeRepository->update($resume);
		$this->flashMessageContainer->add('Your Resume was updated.');
		$this->redirect('list');
	}

	/**
	 * action delete
	 *
	 * @param \Webfox\Placements\Domain\Model\Resume $resume
	 * @return void
	 */
	public function deleteAction(\Webfox\Placements\Domain\Model\Resume $resume) {
		$this->resumeRepository->remove($resume);
		$this->flashMessageContainer->add('Your Resume was removed.');
		$this->redirect('list');
	}

}

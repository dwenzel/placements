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
class UserController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * userRepository
	 *
	 * @var \Webfox\Placements\Domain\Repository\UserRepository
	 * @inject
	 */
	protected $userRepository;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$users = $this->userRepository->findAll();
		$this->view->assign('users', $users);
	}

	/**
	 * action show
	 *
	 * @param \Webfox\Placements\Domain\Model\User $user
	 * @return void
	 */
	public function showAction(\Webfox\Placements\Domain\Model\User $user) {
		$this->view->assign('user', $user);
	}

	/**
	 * action new
	 *
	 * @param \Webfox\Placements\Domain\Model\User $newUser
	 * @dontvalidate $newUser
	 * @return void
	 */
	public function newAction(\Webfox\Placements\Domain\Model\User $newUser = NULL) {
		$this->view->assign('newUser', $newUser);
	}

	/**
	 * action create
	 *
	 * @param \Webfox\Placements\Domain\Model\User $newUser
	 * @return void
	 */
	public function createAction(\Webfox\Placements\Domain\Model\User $newUser) {
		$this->userRepository->add($newUser);
		$this->flashMessageContainer->add('Your new User was created.');
		$this->redirect('list');
	}

	/**
	 * action edit
	 *
	 * @param \Webfox\Placements\Domain\Model\User $user
	 * @return void
	 */
	public function editAction(\Webfox\Placements\Domain\Model\User $user) {
		$this->view->assign('user', $user);
	}

	/**
	 * action update
	 *
	 * @param \Webfox\Placements\Domain\Model\User $user
	 * @return void
	 */
	public function updateAction(\Webfox\Placements\Domain\Model\User $user) {
		$this->userRepository->update($user);
		$this->flashMessageContainer->add('Your User was updated.');
		$this->redirect('list');
	}

	/**
	 * action delete
	 *
	 * @param \Webfox\Placements\Domain\Model\User $user
	 * @return void
	 */
	public function deleteAction(\Webfox\Placements\Domain\Model\User $user) {
		$this->userRepository->remove($user);
		$this->flashMessageContainer->add('Your User was removed.');
		$this->redirect('list');
	}

}

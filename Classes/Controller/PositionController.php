<?php
namespace Webfox\Placements\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Dirk Wenzel <wenzel@webfox01.de>, AgenturWebfox GmbH
 *  Michael Kasten <kasten@webfox01.de>, AgenturWebfox GmbH
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package placements
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class PositionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * positionRepository
	 *
	 * @var \Webfox\Placements\Domain\Repository\PositionRepository
	 * @inject
	 */
	protected $positionRepository;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$positions = $this->positionRepository->findAll();
		$this->view->assign('positions', $positions);
	}

	/**
	 * action show
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $position
	 * @return void
	 */
	public function showAction(\Webfox\Placements\Domain\Model\Position $position) {
		$this->view->assign('position', $position);
	}

	/**
	 * action new
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $newPosition
	 * @dontvalidate $newPosition
	 * @return void
	 */
	public function newAction(\Webfox\Placements\Domain\Model\Position $newPosition = NULL) {
		$this->view->assign('newPosition', $newPosition);
	}

	/**
	 * action create
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $newPosition
	 * @return void
	 */
	public function createAction(\Webfox\Placements\Domain\Model\Position $newPosition) {
		$this->positionRepository->add($newPosition);
		$this->flashMessageContainer->add('Your new Position was created.');
		$this->redirect('list');
	}

	/**
	 * action edit
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $position
	 * @return void
	 */
	public function editAction(\Webfox\Placements\Domain\Model\Position $position) {
		$this->view->assign('position', $position);
	}

	/**
	 * action update
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $position
	 * @return void
	 */
	public function updateAction(\Webfox\Placements\Domain\Model\Position $position) {
		$this->positionRepository->update($position);
		$this->flashMessageContainer->add('Your Position was updated.');
		$this->redirect('list');
	}

	/**
	 * action delete
	 *
	 * @param \Webfox\Placements\Domain\Model\Position $position
	 * @return void
	 */
	public function deleteAction(\Webfox\Placements\Domain\Model\Position $position) {
		$this->positionRepository->remove($position);
		$this->flashMessageContainer->add('Your Position was removed.');
		$this->redirect('list');
	}

}
?>
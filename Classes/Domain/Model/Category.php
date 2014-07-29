<?php
namespace Webfox\Placements\Domain\Model;
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
class Category extends \TYPO3\CMS\Extbase\Domain\Model\Category {
	/**
	 * Category Repository
	 * @var \Webfox\Placements\Domain\Repository\CategoryRepository
	 * @inject
	 */
	protected $categoryRepository;
	
	/**
	 * Returns all child categories of this
	 * @return \array an array of \Webfox\Placements\Domain\Model\Category objects
	 */
	public function getChildren() {
		$children = $this->categoryRepository->findAllChildren($this);
		return $children;
	}

}

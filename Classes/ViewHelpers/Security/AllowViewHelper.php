<?php
namespace Webfox\Placements\ViewHelpers\Security;
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
 * Allow Security ViewHelper.
 *
 * @category    ViewHelpers
 * @package     placements
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author      Dirk Wenzel <wenzel@webfox01.de>
 */
class AllowViewHelper extends AbstractSecurityViewHelper {

	/**
	 * Returns the children
	 *
	 * @return \string
	 */
	public function render() {
		$allowed = $this->evaluate();
		if ($allowed === TRUE) {
			return $this->renderThenChild();
		} else {
			return $this->renderElseChild();
		}
	}

}

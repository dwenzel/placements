<?php
namespace Webfox\Placements\ViewHelpers\Format;
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
 * Render the supplied DateTime object as a formatted date using strftime.
 *
 * @category    ViewHelpers
 * @package     placements
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author      Dirk Wenzel <wenzel@webfox01.de>
 */
class StrftimeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Initialize arguments
	 */
/*	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('date', 'mixed', 'either a DateTime object or a string (UNIX-Timestamp)', TRUE);
		$this->registerArgument('format', 'string', 'Format String which is taken to format the Date/Time');
	}
*/
	/**
	 * Returns the formated Date
	 *
	 * @param mixed $date
	 * @param string $format
	 * @return \string
	 */
	public function render($date = NULL, $format = '%A, %d. %B %Y') {
		if ($date === NULL) {
			$date = $this->renderChildren();
			if ($date === NULL) {
				return '';
			}
		}

		if ($date instanceof \DateTime) {
			try {
				return strftime($format, $date->format('U'));
			} catch (Exception $exception) {
			throw new Tx_Fluid_Core_ViewHelper_Exception('"' . $date . '" was DateTime and could not be converted to UNIX-Timestamp by DateTime.', 200000001);
			}
		}
		return strftime($format, (int)$date);
	}

}

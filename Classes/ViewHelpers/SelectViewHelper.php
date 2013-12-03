<?php
namespace Webfox\Placements\ViewHelpers;

/***************************************************************
*  Copyright notice
*
*  (c) 2013 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
*  Michael Kasten <kasten@webfox01.de>, Agentur Webfox
*  
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
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
 * Extends the standard select viewhelper by option groups.
 *
 * @category    ViewHelpers
 * @package     placements
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author      Dirk Wenzel <wenzel@webfox01.de>
 */
class SelectViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Form\SelectViewHelper {
	/**
	 * Überschreibt die Methode aus dem Fluid-ViewHelper. Es wird geprüft, ob es in dem Option-Objekt eine Methode getChildren() gibt.
	 * Diese sollte Kind-Objekte aus dem Kategoriebaum zurückgeben. Werden Kind-Objekte gefunden, wird eine Option Group
	 *  gerendert, ansonsten die ganz normalen Option-Tags.
	 */
	protected function renderOptionTags($options) {
		$output = '';
		foreach ($options as $value => $option) {
			$children = array();
			if(method_exists($option, 'getChildren') && is_callable(array($option, 'getChildren'))) {
				$children = $option->getChildren();
			}
			if(!empty($children)) {
				$output .= $this->renderOptionGroupTag($this->getLabel($option), $children);
			} else {
				$value = $option->getUid();
				$isSelected = $this->isSelected($value);
				$output.= $this->renderOptionTag($value, $this->getLabel($option), $isSelected) . chr(10);
			}
		}
		return $output;
	}
 
	/**
	 * Auch hier muss eine Methode aus der Fluid-Klasse überschrieben werden, um nicht beim auslesen der Options
	 * die Objekte zu verlieren. Im Fluid-ViewHelper werden an dieser Stelle aus den Objekten bereits Strings gemacht.
	 * Das muss verhindert werden!
	 */
	protected function getOptions() {
		$argOptions = $this->arguments['options'];
		if($argOptions instanceof \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult) {
			$argOptions = $argOptions->toArray();
		}
		if (!is_array($argOptions) && !($argOptions instanceof Traversable)) {
			return array();
		}
		$options = array();
		foreach ($argOptions as $key => $value) {
			if (is_object($value)) {
				if ($this->hasArgument('optionValueField')) {
					$key = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($value, $this->arguments['optionValueField']);
					if (is_object($key)) {
						if (method_exists($key, '__toString')) {
							$key = (string)$key;
						} else {
							throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('Identifying value for object of class "' . get_class($value) . '" was an object.' , 1247827428);
						}
					}
				} elseif ($this->persistenceManager->getBackend()->getIdentifierByObject($value) !== NULL) {
					$key = $this->persistenceManager->getBackend()->getIdentifierByObject($value);
				} elseif (method_exists($value, '__toString')) {
					$key = (string)$value;
				} else {
					throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('No identifying value for object of class "' . get_class($value) . '" found.' , 1247826696);
				}
			}
			$options[$key] = $value;
		}
		if ($this->arguments['sortByOptionLabel']) {
			asort($options);
		}
		return $options;
	}
 
	/**
	 * eine eigene Methode, um aus den Option-Objekten ein String-Label zu holen.
	 */
	protected function getLabel($option) {
		$label = '';
		if ($this->hasArgument('optionLabelField')) {
			$label = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($option, $this->arguments['optionLabelField']);
			if (is_object($label)) {
				if (method_exists($label, '__toString')) {
					$label = (string)$label;
				} else {
					throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('Label value for object of class "' . get_class($label) . '" was an object without a __toString() method.' , 1247827553);
				}
			}
		} elseif (method_exists($option, '__toString')) {
			$label = (string)$option;
		} elseif ($this->persistenceManager->getBackend()->getIdentifierByObject($option) !== NULL) {
			$label = $this->persistenceManager->getBackend()->getIdentifierByObject($option);
		}
		return $label;
	}
 
	/**
	 * Diese Methode rendert ein optgroup-Tag mit untergeordneten Kind-Optionen 
	 */
	protected function renderOptionGroupTag($label, $options) {
		return '<optgroup label="' . htmlspecialchars($label) . '">' . $this->renderOptionTags($options) . '</optgroup>';
	}
}

?>


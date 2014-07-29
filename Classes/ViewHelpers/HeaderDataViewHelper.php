<?php
namespace Webfox\Placements\ViewHelpers;
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
 * ViewHelper to render data in <head> section of website
 *
 * Example
 * <p:headerData>
 * 		<link rel="alternate"
 * 			type="application/rss+xml"
 * 			title="RSS 2.0"
 * 			href="<f:uri.page additionalParams="{type:9818}"/>" />
 * </p:headerData>
 *
 * @package placements
 * @category ViewHelpers
 */
class HeaderDataViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Renders HeaderData
	 *
	 * @return void
	*/
	public function render() {
		$GLOBALS['TSFE']->getPageRenderer()->addHeaderData($this->renderChildren());
	}
}

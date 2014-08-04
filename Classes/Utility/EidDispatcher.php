<?php
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
 * Extbase Dispatcher for Ajax Calls TYPO3 6.1 namespaces
 *
 * IMPORTANT Use this script only in Extensions with namespaces
 *
 * @author Klaus Heuer <klaus.heuer@t3-developer.com>
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * originally written by Klaus Heuer
 * adapted by Dirk Wenzel
 *
 * Usage of this script:
 *
 * - Copy this script in your Extension Dir in the Folder Classes
 * - Set the Vendor and Extension Name in Line 82 + 83
 * - Include the next line in the ext_localconf.php, change the ext name!
 *
 * Use for Ajax Calls in your jQuery Code:
 *
 *     $('.jqAjax').click(function(e)  {
 *       var uid = $(this).find('.uid').html();
 *       var storagePid = '11';
 *      
 *       $.ajax({
 *           async: 'true',
 *           url: 'index.php',      
 *           type: 'POST', 
 *        
 *           data: {
 *               eID: "placementsAjax",  
 *               request: {
 *                   pluginName:  'Placements',
 *                   controller:  'Position',
 *                   action:      'ajaxList',
 *                   arguments: {
 *                       'uid': uid,
 *                       'storagePid': storagePid
 *                   }
 *               }
 *           },
 *           dataType: "json",      
 *          
 *           success: function(result) {
 *               console.log(result);
 *           },
 *           error: function(error) {
 *              console.log(error);               
 *           }
 *       });
 *************************************************************** */
 
 
/**
 * Gets the Ajax Call Parameters
 */
$ajax = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('request');
         
/**
 * Set Vendor and Extension Name
 *
 * Vendor Name like your Vendor Name in namespaces
 * ExtensionName in upperCamelCase
 */
$ajax['vendor'] = 'Webfox';
$ajax['extensionName'] = 'Placements';
         
 
/**
 * @var $objectManager \TYPO3\CMS\Extbase\Object\ObjectManager
 */
$objectManager = new TYPO3\CMS\Extbase\Object\ObjectManager;
 
/**
 * @var $TSFE \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
 */
$TSFE = $objectManager->get('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController', $TYPO3_CONF_VARS, 0, 0);
\TYPO3\CMS\Frontend\Utility\EidUtility::initLanguage();
 
// Get FE User Information
$TSFE->initFEuser();
// Important: no Cache for Ajax stuff
$TSFE->set_no_cache();
 
//$TSFE->checkAlternativCoreMethods();
$TSFE->checkAlternativeIdMethods();
$TSFE->determineId();
$TSFE->initTemplate();
$TSFE->getConfigArray();
\TYPO3\CMS\Core\Core\Bootstrap::getInstance();
 
$TSFE->cObj = $objectManager->get('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');
$TSFE->settingLanguage();
$TSFE->settingLocale();
 
/**
 * Initialize Database
 */
\TYPO3\CMS\Frontend\Utility\EidUtility::connectDB();
 
/**
 * Initialize Extbase bootstap
 */
$bootstrapConf['extensionName'] = $ajax['extensionName'];
$bootstrapConf['pluginName'] = $ajax['pluginName'];
 
$bootstrap = new TYPO3\CMS\Extbase\Core\Bootstrap();
$bootstrap->initialize($bootstrapConf);
 
$bootstrap->cObj = $objectManager->get('tslib_cObj');
 
/**
 * Build the request
 */
$request = $objectManager->get('TYPO3\CMS\Extbase\Mvc\Request');
 
$request->setControllerVendorName($ajax['vendor']);
$request->setcontrollerExtensionName($ajax['extensionName']);
$request->setPluginName($ajax['pluginName']);
$request->setControllerName($ajax['controller']);
$request->setControllerActionName($ajax['action']);
$request->setArguments($ajax['arguments']);
 
$response = $objectManager->get('TYPO3\CMS\Extbase\Mvc\ResponseInterface');
 
$dispatcher = $objectManager->get('TYPO3\CMS\Extbase\Mvc\Dispatcher');
 
$dispatcher->dispatch($request, $response);
 
echo $response->getContent();

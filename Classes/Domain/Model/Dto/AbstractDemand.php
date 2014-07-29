<?php
namespace Webfox\Placements\Domain\Model\Dto;
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
 * Abstract Demand object which holds all common demand properties.
 *
 * @package placements
 */
class AbstractDemand
	extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity implements DemandInterface {

	/**
	 * @var \Webfox\Placements\Domain\Model\Dto\Search
	 */
	protected $search;

	/**
	 * @var \integer
	 */
	protected $status;

	/**
	 * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
	 */
	protected $frontendUser;

	/**
	 * @var \string
	 */
	protected $order;

	/**
	 * @var \string
	 */
	protected $orderBy;

	/**
	 * @var \string
	 */
	protected $orderByAllowed;

	/**
	 * @var \integer
	 */
	protected $storagePage;

	/**
	 * @var integer
	 */
	protected $limit;

	/**
	 * @var integer
	 */
	protected $offset;

	/**
	* @var \array
	*/
	protected $geoLocation;

	/**
	 * @var \integer
	 */
	protected $radius;

	/**
	 * Set search object
	 *
	 * @param \Webfox\Placements\Domain\Model\Dto\Search $search A search object
	 * @return void
	 */
	public function setSearch($search) {
		$this->search = $search;
	}

	/**
	 * Get search 
	 *
	 * @return \Webfox\Placements\Domain\Model\Dto\Search
	 */
	public function getSearch() {
		return $this->search;
	}

	/**
	 * Set status
	 *
	 * @var \integer $status
	 * @return void
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * Get status
	 *
	 * @return \integer
	 */
	public function getStatus() {
	    return $this->status;
	}

	/**
	 * Set Frontend User
	 *
	 * @var \TYPO3\CMS\Domain\Model\FrontendUser $frontendUser
	 * @return void
	 */
	public function setFrontendUser($frontendUser) {
	    $this->frontendUser = $frontendUser;
	}

	/**
	 * Get Frontend User
	 *
	 * @return \TYPO3\CMS\Domain\Model\FrontendUser
	 */
	public function getFrontendUser() {
	    return $this->frontendUser;
	}

	/**
	 * Set order
	 *
	 * @param \string $order
	 * @return void
	 */
	public function setOrder($order) {
		$this->order = $order;
	}

	/**
	 * Get order
	 *
	 * @return \string
	 */
	public function getOrder() {
		return $this->order;
	}

	/**
	 * Set  Order by
	 *
	 * @param \string $orderBy Field name to order by
	 * @return void
	 */
	public function setOrderBy($orderBy) {
		$this->orderBy = $orderBy;
	}

	/**
	 * Get order by
	 * @return \string
	 */
	public function getOrderBy() {
		return $this->orderBy;
	}

	/**
	 * Set order allowed
	 *
	 * @param \string $orderByAllowed allowed fields for ordering
	 * @return void
	 */
	public function setOrderByAllowed($orderByAllowed) {
		$this->orderByAllowed = $orderByAllowed;
	}

	/**
	 * Get allowed order fields
	 *
	 * @return \string
	 */
	public function getOrderByAllowed() {
		return $this->orderByAllowed;
	}

	/**
	 * Set list of storage pages
	 *
	 * @param \string $storagePage storage page list
	 * @return void
	 */
	public function setStoragePage($storagePage) {
		$this->storagePage = $storagePage;
	}

	/**
	 * Get list of storage pages
	 *
	 * @return \string
	 */
	public function getStoragePage() {
		return $this->storagePage;
	}

	/**
	 * Set limit
	 *
	 * @param \integer $limit limit
	 * @return void
	 */
	public function setLimit($limit) {
		$this->limit = (int)$limit;
	}

	/**
	 * Get limit
	 *
	 * @return \integer
	 */
	public function getLimit() {
		return $this->limit;
	}

	/**
	 * Set offset
	 *
	 * @param \integer $offset offset
	 * @return void
	 */
	public function setOffset($offset) {
		$this->offset = (int)$offset;
	}

	/**
	 * Get offset
	 *
	 * @return \integer
	 */
	public function getOffset() {
		return $this->offset;
	}

	/**
	 * Set geo location
	 *
	 * @param \array $geoLocation Geo location: center around which to search for
	 * @return void
	 */
	public function setGeoLocation($geoLocation) {
		$this->geoLocation = $geoLocation;
	}

	/**
	 * Get geo location
	 *
	 * @return \array
	 */
	public function getGeoLocation() {
		return $this->geoLocation;
	}

	/**
	 * Set radius
	 *
	 * @param \integer $radius 
	 * @return void
	 */
	public function setRadius($radius) {
		$this->radius = (int)$radius;
	}

	/**
	 * Get radius
	 *
	 * @return \integer
	 */
	public function getRadius() {
		return $this->radius;
	}

}

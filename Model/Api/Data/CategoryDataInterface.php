<?php

/**
 * @author Mauricio Paz Pacheco
 * @copyright Copyright © 2020 Mpaz. All rights reserved.
 * @package Mpaz_DefaultCategoryData
 */

namespace Mpaz\DefaultCategoryData\Model\Api\Data;

interface CategoryDataInterface
{
	/**
     * Get collection from entity_id
     *
     * @param array $filter - array of categories
     * @return Collection
     */
	public function getCategoryCollection($filter);

	/**
     * Create an array of categories
     *
     * @param Collection $collection
     * @return array
     */
	public function getValues($collection);

	/**
     * Get content from PageBuilder and decode the values
     *
     * @param $value
     * @return HTML
     */
	public function pageBuilderRender($value);
}

<?php 

/**
 * @author Mauricio Paz Pacheco
 * @copyright Copyright © 2020 Mpaz. All rights reserved.
 * @package Mpaz_DefaultCategoryData
 */

namespace Mpaz\DefaultCategoryData\Model;

use \Magento\Framework\Model\AbstractModel;
use \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use \Magento\Catalog\Model\Category;
use \Magento\Cms\Model\Template\FilterProvider;
use \Mpaz\DefaultCategoryData\Model\Api\Data\CategoryDataInterface;

class CategoryDataModel extends AbstractModel implements CategoryDataInterface
{
    /**
     * Resource Model Category CollectionFactory
     *
     * @var CollectionFactory
     */
    protected $_categoryCollectionFactory;

    /**
     * Model category
     *
     * @var Category
     */
    protected $_category;

    /**
     * Cms Model Template FilterProvider;
     *
     * @var FilterProvider
     */
    protected $_filterProvider;

    /**
     * Category Data Model
     *
     * @var CategoryDataModel
     */
    protected $_model;

    /**
     * Construct
     *
     * @param CollectionFactory $categoryCollectionFactory
     * @param Category $category
     * @param FilterProvider $filterProvider
     */
    public function __construct(
        CollectionFactory $categoryCollectionFactory, 
        Category $category,
        FilterProvider $filterProvider)
    {
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_category = $category;
        $this->_filterProvider = $filterProvider;
    }

    /**
     * Get collection from entity_id
     *
     * @param array $filter - array of categories
     * @return Collection
     */
    public function getCategoryCollection($filter) {
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToFilter('entity_id', ['in', $filter]);

        return $collection;
    }

    /**
     * Create an array of categories
     *
     * @param Collection $collection
     * @return array
     */
    public function getValues($collection) {
        $items = [];
        foreach ($collection as $key => $categoryData) {
            $category = $this->_category->load($categoryData->getID());
            $items[$key]['image'] = $category->getImageUrl();
            $items[$key]['name'] = $category->getName();
            $items[$key]['url'] = $categoryData->getUrl();
            if ($category->getDescription())
                $items[$key]['description'] = $this->pageBuilderRender($category->getDescription());
        }

        return $items;
    }

    /**
     * Get content from PageBuilder and decode the values
     *
     * @param $value
     * @return HTML
     */
    public function pageBuilderRender($value) {
        $pureValue = $this->_filterProvider->getPageFilter()->filter($value);

        return $pureValue;
   }
}

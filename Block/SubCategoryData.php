<?php

/**
 * @author Mauricio Paz Pacheco
 * @copyright Copyright © 2020 Mpaz. All rights reserved.
 * @package Mpaz_DefaultCategoryData
 */

namespace Mpaz\DefaultCategoryData\Block;

use \Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context;
use \Mpaz\DefaultCategoryData\Model\CategoryDataModel;
use \Magento\Framework\Registry;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

class SubCategoryData extends Template
{
    protected $_categoryCollectionFactory;

    /**
     * Category Data Model
     *
     * @var CategoryDataModel
     */
    public $_model;


    /**
     * Used to get the current category
     *
     * @var Registry
     */
    public $_registry;

    /**
     * Construct
     *
     * @param Context $context
     * @param CategoryDataModel $model
     */
    public function __construct(
        Context $context,
        CategoryCollectionFactory $categoryCollectionFactory,
        CategoryDataModel $model,
        Registry $registry)
    {
        $this->_registry = $registry;
        $this->_model = $model;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;

        parent::__construct($context);
    }

    /**
    * Get the sub categories
    *
    * @return array
    */
    public function getSubCategories($id = null) {
        if (!$id) {
            return null;
        }
        
        return $this->_model->getDescendants($id);
    }

    /**
    * Get the current category
    *
    * @return Category
    */
    public function getSubcategoryCollection() {
        $current = $this->_registry->registry('current_category')->getId();
        return $this->_model->getValues($this->getSubcategoriesByParentIds($current));
    }

    /**
     * Returns the sub categories from a parent ID
     *
     * @param Category $parentIds
     * @return Collection
     */
    public function getSubcategoriesByParentIds($parentIds) {
        $parentIds = is_array($parentIds) ? $parentIds : [$parentIds];
        
        $collection = $this->getSubcategoriesCollection();
        $collection->addAttributeToFilter('parent_id', ['in' => $parentIds]);
        
        return $collection->setPageSize(5)->load();
    }

    /**
     * Returns the sub categories
     *
     * @return Collection
     */
    public function getSubcategoriesCollection()
    {
        $collection = $this->_categoryCollectionFactory->create();
        
        $collection->addAttributeToSelect('*')
            ->addAttributeToFilter('is_active', '1')
            ->setStore($this->_storeManager->getStore())
            ->setOrder($this->getSortOrder(), 'ASC');
        
        if ($this->getCategoryLimit()) {
            $collection->setPageSize($this->getCategoryLimit());
        }
        
        return $collection;
    }

    /**
     * Used to get the limit
     *
     * @return Integer
     */
    public function getCategoryLimit()
    {
        return $this->getData("limit") ?: 0;
    }
}

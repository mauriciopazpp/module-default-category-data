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
use \Magento\Catalog\Helper\ImageFactory;
use \Magento\Framework\View\Asset\Repository as Assets;
use \Magento\Framework\Registry;

class CategoryData extends Template
{
    /**
     * Category Data Model
     *
     * @var CategoryDataModel
     */
    public $_model;
    
    /**
     * Used to get the default image
     *
     * @var ImageFactory
     */
    public $_imageFactory;

    /**
     * Used to get the url of the placeholder image
     *
     * @var Assets
     */
    public $_assets;

    /**
     * Used to get the current category
     *
     * @var Registry
     */
    public $_registry;

    /**
     * Construct
     *
     * @param CollectionFactory $categoryCollectionFactory
     * @param Category $category
     * @param FilterProvider $filterProvider
     * @param ImageFactory $imageFactory
     * @param Assets $assets
     */
    public function __construct(
        Context $context,
        CategoryDataModel $model,
        ImageFactory $imageFactory,
        Assets $assets,
        Registry $registry)
    {
        $this->_model = $model;
        $this->_imageFactory = $imageFactory;
        $this->_assets = $assets;
        $this->_registry = $registry;

        parent::__construct($context);
    }

    /**
    * Get the categories defined at the block into the admin panel
    *
    * @return array
    */
    public function getCategories($ids = []) {
        if (!$ids) {
            return [];
        }

        return $this->_model->getValues(
            $this->_model->getCategoryCollection(explode(',', $ids))
        );
    }

    /**
    * Get the current category
    *
    * @return Category 
    */
    public function getCurrentCategory() {
        return $this->_registry->registry('current_category');
    }

    /**
     * Get place holder image of a product for small_image
     *
     * @return string
     */
    public function getPlaceHolderImage()
    {
        $image = $this->_imageFactory->create();
        return $this->_assets->getUrl($image->getPlaceholder('small_image'));
    }
}

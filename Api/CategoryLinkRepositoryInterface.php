<?php

namespace Experius\CategoryMultipleProductsApi\Api;

/**
 * @api
 */
interface CategoryLinkRepositoryInterface
{
    /**
     * Replace category products
     *
     * @param int $categoryId
     * @param \Magento\Catalog\Api\Data\CategoryProductLinkInterface[] $productLinks
     * @return bool will returned True if assigned
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function replace($categoryId, $productLinks);
    
    /**
     * Save multiple category products
     *
     * @param int $categoryId
     * @param \Magento\Catalog\Api\Data\CategoryProductLinkInterface[] $productLinks
     * @return bool will returned True if assigned
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function save($categoryId, $productLinks);
}

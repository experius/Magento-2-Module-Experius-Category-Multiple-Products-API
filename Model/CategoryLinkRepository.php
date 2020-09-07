<?php

namespace Experius\CategoryMultipleProductsApi\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class CategoryLinkRepository extends \Magento\Catalog\Model\CategoryLinkRepository implements \Experius\CategoryMultipleProductsApi\Api\CategoryLinkRepositoryInterface
{
    
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    protected $productResourceModel;
    
    /**
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\ResourceModel\Product $productResourceModel
    ) {
        parent::__construct($categoryRepository, $productRepository);
        $this->productResourceModel = $productResourceModel;
    }
    
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function replace($categoryId, $productLinks)
    {
        $messages = [];
        try {
            $category = $this->categoryRepository->get($categoryId);
            $productPositions = [];
            $currentProductPositions = $category->getProductsPosition();
            foreach ($productLinks as $productLink){
                try {
                    $productId = $this->productResourceModel->getIdBySku($productLink->getSku());
                    if ($productId){
                        $position = $productLink->getPosition();
                        if (!$position && isset($currentProductPositions[$productId])) {
                            $position = $currentProductPositions[$productId];
                        }
                        $productPositions[$productId] = $position;
                    }
                } catch (NoSuchEntityException $e){
                    $messages[] = "Product with SKU: " . $productLink->getSku() . ", does not exist.";
                }
            }
            $category->setPostedProducts($productPositions);
            try {
                $category->save();
            } catch (\Exception $e) {
                throw new CouldNotSaveException(
                    __(
                        'Could not save products to category %1',
                        $category->getId()
                    ),
                    $e
                );
            }
        } catch (NoSuchEntityException $e){
            $messages[] = "Category with ID: " . $categoryId . ", does not exist.";
        }
        if (!empty($messages)){
            return $messages;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function saveMultiple($categoryId, $productLinks)
    {
        $messages = [];
        try {
            $category = $this->categoryRepository->get($categoryId);
            $productPositions = $category->getProductsPosition();
            foreach ($productLinks as $productLink){
                try {
                    $productId = $this->productResourceModel->getIdBySku($productLink->getSku());
                    if ($productId){
                        $productPositions[$productId] = $productLink->getPosition();
                    }
                } catch (NoSuchEntityException $e){
                    $messages[] = "Product with SKU: " . $productLink->getSku() . ", does not exist.";
                }
            }
            $category->setPostedProducts($productPositions);
            try {
                $category->save();
            } catch (\Exception $e) {
                throw new CouldNotSaveException(
                    __(
                        'Could not save products to category %1',
                        $category->getId()
                    ),
                    $e
                );
            }
        } catch (NoSuchEntityException $e){
            $messages[] = "Category with ID: " . $categoryId . ", does not exist.";
        }
        if (!empty($messages)){
            return $messages;
        }
        return true;
    }

}

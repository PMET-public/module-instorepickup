<?php

namespace MagentoEse\InStorePickup\Block\Product\View;

use Magento\Catalog\Api\ProductRepositoryInterface;
use MagentoEse\InStorePickup\Model\StoreLocationCookieManager;
use MagentoEse\InStorePickup\Model\StoreLocation;

/**
 * In Store Pickup block
 */
class InStorePickup extends \Magento\Framework\View\Element\Template
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param ProductRepositoryInterface|\Magento\Framework\Pricing\PriceCurrencyInterface $productRepository
     * @param StoreLocationCookieManager $storeLocationCookieManager
     * @param StoreLocation $storeLocation
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
//        $this->_isScopePrivate = true;

        $this->_coreRegistry = $context->getRegistry();
        $this->productRepository = $productRepository;
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Retrieve current product model
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        if (!$this->_coreRegistry->registry('product') && $this->getProductId()) {
            $product = $this->productRepository->getById($this->getProductId());
            $this->_coreRegistry->register('product', $product);
        }
        return $this->_coreRegistry->registry('product');
    }

    /**
     * Check whether in store pickup is available
     *
     * @return bool
     */
    public function isAvailableInStore()
    {
        return $this->getDefaultAttributeText($this->getProduct(), 'in_store_available') == 'In Store';
    }

    public function getAvailabilityText()
    {
        return $this->getProduct()->getAttributeText('in_store_available');
    }

    /**
     * Returns the default attribute text value/s for a product
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $attributeCode
     * @return string|array
     */
    private function getDefaultAttributeText(\Magento\Catalog\Model\Product $product, $attributeCode)
    {
        $value = $product->getData($attributeCode);

        // Reference:
        //  \Magento\Eav\Model\Entity\Attribute\Source\Table::getOptionText
        $isMultiple = false;
        if (strpos($value, ',')) {
            $isMultiple = true;
            $value = explode(',', $value);
        }

        // get the default (Admin store_id=0) values for the options
        // Reference:
        //  \Magento\Catalog\Model\Product::getAttributeText
        //  \Magento\Eav\Model\Entity\Attribute\Source\Table::getAllOptions
        //  \Magento\Eav\Model\Entity\Attribute\Source\Table::getOptionText
        $options = $product->getResource()->getAttribute($attributeCode)->getSource()->getAllOptions(false, true);

        if ($isMultiple) {
            $values = [];
            foreach ($options as $item) {
                if (in_array($item['value'], $value)) {
                    $values[] = $item['label'];
                }
            }
            return $values;
        }

        foreach ($options as $item) {
            if ($item['value'] == $value) {
                return $item['label'];
            }
        }
        return '';
    }
}

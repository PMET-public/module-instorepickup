<?php

namespace MagentoEse\InStorePickup\Block\Product\View;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\Product;

/**
 * In Store Pickup block for Product View pages
 */
class InStorePickup extends Template
{
    /**
     * Values used in admin text for in_store_avialable product attribute
     */
    const ADMIN_IN_STORE_AVAILABLE_YES = 'In Store';
    const ADMIN_IN_STORE_AVAILABLE_NO = 'Online Exclusive';

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
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
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
        return $this->_coreRegistry->registry('product');
    }

    /**
     * Check whether in store pickup is available
     *
     * @return bool
     */
    public function isAvailableInStore()
    {
        return $this->getDefaultAttributeText($this->getProduct(), 'in_store_available') == self::ADMIN_IN_STORE_AVAILABLE_YES;
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
    private function getDefaultAttributeText(Product $product, $attributeCode)
    {
        $value = $product->getData($attributeCode);

        // Reference:
        //  \Magento\Eav\Model\Entity\Attribute\Source\Table::getOptionText
        $isMultiple = false;
        if (strpos($value, ',') !== false) {
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

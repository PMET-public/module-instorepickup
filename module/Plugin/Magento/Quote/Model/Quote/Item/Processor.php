<?php

namespace MagentoEse\InStorePickup\Plugin\Magento\Quote\Model\Quote\Item;

/**
 * Plugin for adding data to the quote item
 */
class Processor
{

    /**
     * Setup product for quote item
     *
     * @param \Magento\Quote\Model\Quote\Item\Processor $subject
     * @param callable $proceed
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Framework\DataObject $request
     * @return \Magento\Quote\Model\Quote\Item
     */
    public function aroundInit(
        \Magento\Quote\Model\Quote\Item\Processor $subject,
        \Closure $proceed,
        \Magento\Catalog\Model\Product $product,
        $request
    ) {
        // Let the original class execute
        $result = $proceed($product, $request);

        // Add additional attributes to the quote
        $result->setInstorepickupAddtocartMethod($request->getInstorepickupAddtocartMethod());

        // Return the
        return $result;
    }
}

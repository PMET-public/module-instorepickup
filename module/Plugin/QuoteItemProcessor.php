<?php

namespace MagentoEse\InStorePickup\Plugin;

use Magento\Quote\Model\Quote\Item;
use Magento\Quote\Model\Quote\Item\Processor;
use Magento\Catalog\Model\Product;

/**
 * Plugin for adding data to the quote item
 */
class QuoteItemProcessor
{

    /**
     * Setup product for quote item
     *
     * @param Processor $subject
     * @param \Closure $proceed
     * @param Product $product
     * @param \Magento\Framework\DataObject $request
     * @return Item
     */
    public function aroundInit(
        Processor $subject,
        \Closure $proceed,
        Product $product,
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

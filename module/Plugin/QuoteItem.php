<?php

namespace MagentoEse\InStorePickup\Plugin;

use Closure;
use Magento\Sales\Model\Order\Item;

class QuoteItem
{
    /**
     * @param \Magento\Quote\Model\Quote\Item\ToOrderItem $subject
     * @param callable $proceed
     * @param \Magento\Quote\Model\Quote\Item\AbstractItem $item
     * @param array $additional
     * @return Item
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundConvert(
        \Magento\Quote\Model\Quote\Item\ToOrderItem $subject,
        Closure $proceed,
        \Magento\Quote\Model\Quote\Item\AbstractItem $item,
        $additional = []
    ) {
        /** @var $orderItem \Magento\Sales\Model\Order\Item */
        $orderItem = $proceed($item, $additional);

        $addtocartMethod = $item->getInstorepickupAddtocartMethod();
        if ($item instanceof \Magento\Quote\Model\Quote\Address\Item) {
            $addtocartMethod = $item->getQuoteItem()->getInstorepickupAddtocartMethod();
        }

        $orderItem->setInstorepickupAddtocartMethod($addtocartMethod);

        return $orderItem;
    }
}

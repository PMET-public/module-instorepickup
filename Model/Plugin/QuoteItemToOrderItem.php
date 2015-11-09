<?php

namespace MagentoEse\InStorePickup\Model\Plugin;

use Closure;
use Magento\Quote\Model\Quote\Item\ToOrderItem;
use Magento\Sales\Model\Order\Item;
use Magento\Quote\Model\Quote\Item\AbstractItem;

class QuoteItemToOrderItem
{
    /**
     * @param ToOrderItem $subject
     * @param Closure $proceed
     * @param AbstractItem $item
     * @param array $additional
     * @return Item
     */
    public function aroundConvert(
        ToOrderItem $subject,
        Closure $proceed,
        AbstractItem $item,
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

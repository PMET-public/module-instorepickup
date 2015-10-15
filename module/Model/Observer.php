<?php

namespace MagentoEse\InStorePickup\Model;

/**
 * InStorePickup Observer Model
 *
 */
class Observer
{
    /**
     * Set InStorePickup attributes to order from quote
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function salesEventQuoteSubmitBefore($observer)
    {
        $observer->getEvent()->getOrder()->setHasInstorepickupFulfillment($observer->getEvent()->getQuote()->getHasInstorepickupFulfillment());
        $observer->getEvent()->getOrder()->setInstorepickupStoreLocationId($observer->getEvent()->getQuote()->getInstorepickupStoreLocationId());
        return $this;
    }

    /**
     * Set InStorePickup attributes to order from quote address in multiple addresses checkout.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function multishippingEventCreateOrders($observer)
    {
        $observer->getEvent()->getOrder()->setHasInstorepickupFulfillment($observer->getEvent()->getQuote()->getHasInstorepickupFulfillment());
        $observer->getEvent()->getOrder()->setInstorepickupStoreLocationId($observer->getEvent()->getQuote()->getInstorepickupStoreLocationId());
        return $this;
    }
}

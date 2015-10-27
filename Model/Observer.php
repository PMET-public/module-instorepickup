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
        $observer->getEvent()->getOrder()->setInstorepickupStoreLocationName($observer->getEvent()->getQuote()->getInstorepickupStoreLocationName());
        $observer->getEvent()->getOrder()->setInstorepickupStoreLocationCity($observer->getEvent()->getQuote()->getInstorepickupStoreLocationCity());
        $observer->getEvent()->getOrder()->setInstorepickupStoreLocationState($observer->getEvent()->getQuote()->getInstorepickupStoreLocationState());
        $observer->getEvent()->getOrder()->setInstorepickupStoreLocationPostalCode($observer->getEvent()->getQuote()->getInstorepickupStoreLocationPostalCode());
        $observer->getEvent()->getOrder()->setInstorepickupStoreLocationPhone($observer->getEvent()->getQuote()->getInstorepickupStoreLocationPhone());
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
        $observer->getEvent()->getOrder()->setInstorepickupStoreLocationName($observer->getEvent()->getQuote()->getInstorepickupStoreLocationName());
        $observer->getEvent()->getOrder()->setInstorepickupStoreLocationCity($observer->getEvent()->getQuote()->getInstorepickupStoreLocationCity());
        $observer->getEvent()->getOrder()->setInstorepickupStoreLocationState($observer->getEvent()->getQuote()->getInstorepickupStoreLocationState());
        $observer->getEvent()->getOrder()->setInstorepickupStoreLocationPostalCode($observer->getEvent()->getQuote()->getInstorepickupStoreLocationPostalCode());
        $observer->getEvent()->getOrder()->setInstorepickupStoreLocationPhone($observer->getEvent()->getQuote()->getInstorepickupStoreLocationPhone());
        return $this;
    }
}

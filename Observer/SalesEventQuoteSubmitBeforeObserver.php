<?php

namespace MagentoEse\InStorePickup\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

/**
 * In-Store Pickup Observer Model
 */
class SalesEventQuoteSubmitBeforeObserver implements ObserverInterface
{
    /**
     * Set InStorePickup attributes to order from quote address
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
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

<?php

namespace MagentoEse\InStorePickup\Plugin;

use Closure;
use MagentoEse\InStorePickup\Model\Session;
use Magento\Quote\Model\Quote\Item;

class Quote
{
    /**
     * Store Location session
     *
     * @var Session\StoreLocation
     */
    protected $storeLocationSession;

    /**
     * @param Session\StoreLocation $storeLocationSession
     */
    public function __construct(
        Session\StoreLocation $storeLocationSession
    ) {
        $this->storeLocationSession = $storeLocationSession;
    }

    /**
     * @param \Magento\Quote\Model\Quote $subject
     * @param Closure $proceed
     * @param \Magento\Quote\Model\Quote\Item $item
     * @return \Magento\Quote\Model\Quote
     */
    public function aroundAddItem(
        \Magento\Quote\Model\Quote $subject,
        Closure $proceed,
        Item $item
    ) {
        /** @var $quote \Magento\Quote\Model\Quote */
        $quote = $proceed($item);

        // Set some quote attribute to flag the existance of in store pickup items and the store location id used
        if ($item->getInstorepickupAddtocartMethod() == 'pick-up') {
            $quote->setHasInstorepickupFulfillment(1);
            $quote->setInstorepickupStoreLocationId($this->storeLocationSession->getStoreLocation()->getId());
            $quote->setInstorepickupStoreLocationName($this->storeLocationSession->getStoreLocation()->getName());
            $quote->setInstorepickupStoreLocationCity($this->storeLocationSession->getStoreLocation()->getCity());
            $quote->setInstorepickupStoreLocationState($this->storeLocationSession->getStoreLocation()->getState());
            $quote->setInstorepickupStoreLocationPostalCode($this->storeLocationSession->getStoreLocation()->getPostalCode());
            $quote->setInstorepickupStoreLocationPhone($this->storeLocationSession->getStoreLocation()->getPhone());
            $quote->setInstorepickupStoreLocationStreet($this->storeLocationSession->getStoreLocation()->getStreetAddress());
        }

        return $quote;
    }

    /**
     * @param \Magento\Quote\Model\Quote $subject
     * @param \Magento\Quote\Model\Quote $result
     * @return \Magento\Quote\Model\Quote
     */
    public function afterRemoveItem(
        \Magento\Quote\Model\Quote $subject,
        \Magento\Quote\Model\Quote $result
    ) {
        return $this->CheckQuoteAfterItemRemoved($result);
    }


    /**
     * @param \Magento\Quote\Model\Quote $subject
     * @param \Magento\Quote\Model\Quote $result
     * @return \Magento\Quote\Model\Quote
     */
    public function afterDeleteItem(
        \Magento\Quote\Model\Quote $subject,
        \Magento\Quote\Model\Quote $result
    ) {
        return $this->CheckQuoteAfterItemRemoved($result);
    }


    /**
     * @param \Magento\Quote\Model\Quote $subject
     * @param \Magento\Quote\Model\Quote $result
     * @return \Magento\Quote\Model\Quote
     */
    public function afterRemoveAllItems(
        \Magento\Quote\Model\Quote $subject,
        \Magento\Quote\Model\Quote $result
    ) {
        return $this->CheckQuoteAfterItemRemoved($result);
    }

    /**
     * @param \Magento\Quote\Model\Quote $result
     * @return \Magento\Quote\Model\Quote
     */
    private function CheckQuoteAfterItemRemoved(\Magento\Quote\Model\Quote $result) {

        // Loop through all items to check for the existance of any in store pickup items
        $foundInstorepickupItem = false;
        foreach ($result->getAllVisibleItems() as $item)
        {
            if ($item->getInstorepickupAddtocartMethod() == 'pick-up') {
                $foundInstorepickupItem = true;
            }
        }

        // If there are no longer any items for in store pickup, remove the flag and store location id
        if ($foundInstorepickupItem == false) {
            $result->setHasInstorepickupFulfillment(0);
            $result->setInstorepickupStoreLocationId(0);
            $result->setInstorepickupStoreLocationName(null);
            $result->setInstorepickupStoreLocationCity(null);
            $result->setInstorepickupStoreLocationState(null);
            $result->setInstorepickupStoreLocationPostalCode(null);
            $result->setInstorepickupStoreLocationPhone(null);
            $result->setInstorepickupStoreLocationStreet(null);
        }

        return $result;
    }

    /**
     * @param \Magento\Quote\Model\Quote $subject
     * @param $result
     * @return bool
     */
    public function afterIsVirtual(
        \Magento\Quote\Model\Quote $subject,
        $result
    ) {
        // If it has already been determined that this is a virtual order there is no additional evaluation needed
        if ($result == true) {
            return $result;
        }

        // If a quote contains only in store pickup items it should be treated as virtual
        // Loop through all items to check if any items are not in store pickup items
        $containsOnlyInStorePickupItems = true;
        foreach ($subject->getAllVisibleItems() as $item)
        {
            if ($item->getInstorepickupAddtocartMethod() != 'pick-up') {
                $containsOnlyInStorePickupItems = false;
            }
        }

        return $containsOnlyInStorePickupItems;
    }
}

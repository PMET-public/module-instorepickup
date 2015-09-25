<?php

namespace MagentoEse\InStorePickup\Block;

use MagentoEse\InStorePickup\Model\StoreLocationCookieManager;
use MagentoEse\InStorePickup\Model\StoreLocation;

/**
 * Store Location Detail block
 */
class StoreDetails extends StoreNav
{
    /**
     * Retrieve directions url
     *
     * @param StoreLocation $storeLocation
     * @return string
     */
    public function getDirectionsUrl(StoreLocation $storeLocation)
    {
        // Load the chosen store
        $this->getChosenStoreLocation();

        // Build a string representing the store address
        $destinationAddress =
            $storeLocation->getStreetAddress() . ", " .
            $storeLocation->getCity() . ", " . $storeLocation->getState() . " " . $storeLocation->getPostalCode();

        // URL Encode the address
        $destinationAddress = urlencode($destinationAddress);

        $url = "http://maps.google.com/maps?daddr=" . $destinationAddress;
        return $url;
    }
}

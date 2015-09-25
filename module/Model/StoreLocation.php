<?php

namespace MagentoEse\InStorePickup\Model;
/**
 * Store Location model
 *
 * @method string getName()
 * @method string getStreetAddress()
 * @method string getCity()
 * @method string getState()
 * @method string getPostalCode()
 * @method string getPhone()
 */
class StoreLocation extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('MagentoEse\InStorePickup\Model\Resource\StoreLocation');
    }
}

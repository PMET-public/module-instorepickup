<?php

namespace MagentoEse\InStorePickup\Model\Resource;

/**
 * Store Location resource model
 *
 * @method string getId()
 * @method string getName()
 * @method string getStreetAddress()
 * @method string getCity()
 * @method string getState()
 * @method string getPostalCode()
 * @method string getPhone()
 */
class StoreLocation extends \Magento\Framework\Model\Resource\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('directory_location_pickup_store', 'id');
    }
}

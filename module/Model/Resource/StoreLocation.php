<?php

namespace MagentoEse\InStorePickup\Model\Resource;

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

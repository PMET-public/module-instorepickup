<?php

namespace MagentoEse\InStorePickup\Model\ResourceModel;

/**
 * Store Location resource model
 *
 * @method string getId()
 * @method string getName()
 * @method string getStreet()
 * @method string getCity()
 * @method string getRegion()
 * @method string getPostcode()
 * @method string getPhone()
 */
class StoreLocation extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('inventory_source', 'source_code');
    }
}

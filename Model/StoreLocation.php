<?php

namespace MagentoEse\InStorePickup\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Store Location model
 *
 * @method string getId()
 * @method string getName()
 * @method string getStreet()
 * @method string getCity()
 * @method string getState()
 * @method string getPostcode()
 * @method string getPhone()
 */
class StoreLocation extends AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('MagentoEse\InStorePickup\Model\ResourceModel\StoreLocation');
    }
}

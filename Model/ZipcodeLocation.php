<?php

namespace MagentoEse\InStorePickup\Model;

use Magento\Framework\Model\AbstractModel;

class ZipcodeLocation extends AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('MagentoEse\InStorePickup\Model\ResourceModel\ZipcodeLocation');
    }
}

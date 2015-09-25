<?php

namespace MagentoEse\InStorePickup\Model;

class ZipcodeLocation extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('MagentoEse\InStorePickup\Model\Resource\ZipcodeLocation');
    }
}

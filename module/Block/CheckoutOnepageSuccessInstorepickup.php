<?php

namespace MagentoEse\InStorePickup\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;

/**
 * Store Selector block
 */
class CheckoutOnepageSuccessInstorepickup extends Template
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @param Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        array $data = []
    ) {
        $this->_checkoutSession = $checkoutSession;
        parent::__construct($context, $data);
    }

    /**
     * Flag indicating if an order contained in-store pickup items
     *
     * @return bool
     */
    public function orderContainedInstorepickupItems()
    {
        $order = $this->_checkoutSession->getLastRealOrder();

        return $order->getHasInstorepickupFulfillment();
    }
}

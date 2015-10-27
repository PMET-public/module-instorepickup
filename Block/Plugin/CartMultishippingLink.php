<?php

namespace MagentoEse\InStorePickup\Block\Plugin;

class CartMultishippingLink
{
    /**
     * Checkout session
     *
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @param \Magento\Multishipping\Block\Checkout\Link $subject
     * @param string $result
     * @return string
     */
    public function after_toHtml(
        \Magento\Multishipping\Block\Checkout\Link $subject,
        $result
    ) {

        // Check to see if a quote exists, and if it has any In-Store Pickup items
        if ($this->checkoutSession->hasQuote() && $this->checkoutSession->getQuote()->getHasInstorepickupFulfillment()) {

            // If In-Store Pickup items exist, then hide the multishipping link
            return '';
        }
        return $result;
    }
}
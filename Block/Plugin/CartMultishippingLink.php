<?php

namespace MagentoEse\InStorePickup\Block\Plugin;

use Magento\Checkout\Model\Session;
use Magento\Multishipping\Block\Checkout\Link;

class CartMultishippingLink
{
    /**
     * Checkout session
     *
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @param Session $checkoutSession
     */
    public function __construct(
        Session $checkoutSession
    ) {
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @param Link $subject
     * @param string $result
     * @return string
     */
    public function after_toHtml(
        Link $subject,
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
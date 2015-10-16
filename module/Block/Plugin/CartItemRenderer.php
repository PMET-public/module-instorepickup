<?php

namespace MagentoEse\InStorePickup\Block\Plugin;

use Magento\Checkout\Block\Cart\Item\Renderer;

class CartItemRenderer
{
    const TEMPLATE_PATH = 'MagentoEse_InStorePickup::cart/item/default.phtml';

    /**
     * Override the template that is set for item renderers because there is no way to do this in
     * a layout file because the blocks use anonymous block names in the render list.
     *
     * @param Renderer $subject
     * @param string $value
     * @return Renderer
     */
    public function beforeSetTemplate(
        Renderer $subject,
        $value
    ) {
        return array(self::TEMPLATE_PATH);
    }
}
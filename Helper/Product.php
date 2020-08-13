<?php

namespace MagentoEse\InStorePickup\Helper;

use Magento\Catalog\Block\Product\View\AbstractView;

class Product extends AbstractView
{

    public function getProductId()
    {

        if($this->getProduct()){
            return $this->getProduct()->getId();
        }else{
            return 0;
        }

    }
}

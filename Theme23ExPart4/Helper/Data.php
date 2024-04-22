<?php
namespace Perspective\Theme23ExPart4\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Data extends AbstractHelper
{

    /**
     * @param Context $context
     */

    public function __construct(Context $context)
    {        
        parent::__construct($context);
    }

    // --- --- Get info --- --- //

    public function getOrderPrice($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue(
            'perspective/general/order_price',
            $scope
        );
    }

    public function getPaymentMethodLargeWholesale($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue(
            'perspective/general/payment_method_large_wholesale',
            $scope
        );
    }

    public function getNumberOfUnitsOfGoods($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue(
            'perspective/general/number_of_units_of_goods',
            $scope
        );
    }

    public function getPaymentMethodWholesale($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue(
            'perspective/general/payment_method_wholesale',
            $scope
        );
    }

    public function getShippingMethodWholesale($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue(
            'perspective/general/shipping_method_wholesale',
            $scope
        );
    }
    // --- --- -------- --- --- //
}

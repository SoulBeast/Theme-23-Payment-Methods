<?php

namespace Perspective\Theme23ExPart4\Plugin\Model\Method;

use Perspective\Theme23ExPart4\Helper\Data;

class Available extends \Magento\Framework\Session\SessionManager
{
    protected $customerSession;
    protected $_checkoutSession;
    protected $helper;

    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        Data $helper,
    ) {
        $this->customerSession = $customerSession;
        $this->_checkoutSession = $checkoutSession;
        $this->helper = $helper;
    }

    public function aroundCollectCarrierRates(
        \Magento\Shipping\Model\Shipping $subject,
        \Closure $proceed,
        $carrierCode,
        $request
    ) {
        $group = $this->customerSession->getCustomer()->getGroupId();
        $orderPrice = $this->getOrderPrice();
        $quote = $this->getCheckoutSession()->getQuote();
        $grandTotal = $quote->getGrandTotal();
        $qty = $quote->getItemsQty();
        $shippingMethod = $this->getShippingMethodWholesale();
        $numberOfUnits = $this->getNumberOfUnitsOfGoods();
        $shippingCarrierCode = $carrierCode . "_" . $carrierCode;
        if ($group == 5) {
            if ($orderPrice < $grandTotal) {
                // Hide all shipping methods except free shipping method
                if ($shippingCarrierCode != "freeshipping_freeshipping") {
                    return false;
                } 

                $result = $proceed($carrierCode, $request);
                return $result;
            } else {
                // Hide all shipping methods except free shipping method
                if ($shippingCarrierCode != "freeshipping_freeshipping") {
                    return false;
                } 

                $result = $proceed($carrierCode, $request);
                return $result;
            }
        } elseif ($group == 6){
            if ($numberOfUnits < $qty) {
                // Hide all shipping methods except free shipping method
                if ($shippingCarrierCode != $shippingMethod) {
                    return false;
                } 

                $result = $proceed($carrierCode, $request);
                return $result;
            } else {
                // Hide all shipping methods except free shipping method
                if ($shippingCarrierCode == "freeshipping_freeshipping") {
                    return false;
                } 

                $result = $proceed($carrierCode, $request);
                return $result;
            }
        }
        $result = $proceed($carrierCode, $request);
        return $result;
    }

    public function afterGetAvailableMethods($subject, $result)
    {
        $quote = $this->getCheckoutSession()->getQuote();
        $grandTotal = $quote->getGrandTotal();
        $group = $this->customerSession->getCustomer()->getGroupId();
        $orderPrice = $this->getOrderPrice();
        $largePaymentMethod = $this->getPaymentMethodLargeWholesale();
        $wholesalePaymentMethod = $this->getPaymentMethodWholesale();
        $numberOfUnits = $this->getNumberOfUnitsOfGoods();
        $qty = $quote->getItemsQty();
        if ($group == 5) {
            if ($orderPrice < $grandTotal) {
                foreach ($result as $key => $_result) {
                    if ($_result->getCode() != $largePaymentMethod) {
                        $isAllowed = false;
                        if (!$isAllowed) {
                            unset($result[$key]);
                        }
                    }
                }
            } else {
                return $result;
            }
        } elseif ($group == 6){
            if ($numberOfUnits < $qty) {
                foreach ($result as $key => $_result) {
                    if ($_result->getCode() != $wholesalePaymentMethod) {
                        $isAllowed = false;
                        if (!$isAllowed) {
                            unset($result[$key]);
                        }
                    }
                }
            } else {
                return $result;
            }
        }
        return $result;
    }

    public function getCheckoutSession()
    {
        return $this->_checkoutSession;
    }

    public function getOrderPrice()
    {
        return $this->helper->getOrderPrice();
    }

    public function getPaymentMethodLargeWholesale()
    {
        return $this->helper->getPaymentMethodLargeWholesale();
    }

    public function getNumberOfUnitsOfGoods()
    {
        return $this->helper->getNumberOfUnitsOfGoods();
    }

    public function getPaymentMethodWholesale()
    {
        return $this->helper->getPaymentMethodWholesale();
    }

    public function getShippingMethodWholesale()
    {
        return $this->helper->getShippingMethodWholesale();
    }
}

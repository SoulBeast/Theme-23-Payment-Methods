<?php
namespace Perspective\Theme23ExPart4\Model\Config\Source;

class WholesalePayment implements \Magento\Framework\Data\OptionSourceInterface
{
    protected $paymentMethodList;
    protected $_storeManager;

    public function __construct(
        \Magento\Payment\Model\PaymentMethodList $paymentMethodList,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
    ) {
        $this->paymentMethodList = $paymentMethodList;
        $this->_storeManager = $storeManager;
    }

    public function getMethods()
    {
        $storeId = $this->_storeManager->getStore()->getId();
        return $this->paymentMethodList->getActiveList($storeId);
    }    

    public function toOptionArray()
    {  
        $array = [];
        $methods = $this->getMethods();
        foreach ($methods as $method){
            $array[] = ['value' => $method->getCode(), 'label' => __($method->getTitle())];
        }

    return $array;
    }
}

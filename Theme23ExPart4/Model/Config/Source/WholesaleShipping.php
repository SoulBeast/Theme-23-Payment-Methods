<?php
namespace Perspective\Theme23ExPart4\Model\Config\Source;

class WholesaleShipping implements \Magento\Framework\Data\OptionSourceInterface
{
    protected $paymentMethodList;
    protected $_storeManager;
    protected $scopeConfig; 
    protected $shipconfig;

    public function __construct(
        \Magento\Payment\Model\PaymentMethodList $paymentMethodList,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Shipping\Model\Config $shipconfig
    ) {
        $this->paymentMethodList = $paymentMethodList;
        $this->_storeManager = $storeManager;
        $this->shipconfig = $shipconfig;
        $this->scopeConfig = $scopeConfig;
    }


    public function getShippingMethods() {
        $activeCarriers = $this->shipconfig->getActiveCarriers();
    
        foreach($activeCarriers as $carrierCode => $carrierModel) {
           $options = array();
    
           if ($carrierMethods = $carrierModel->getAllowedMethods()) {
               foreach ($carrierMethods as $methodCode => $method) {
                    $code = $carrierCode . '_' . $methodCode;
                    $options[] = array('value' => $code, 'label' => $method);
               }
               $carrierTitle = $this->scopeConfig
                   ->getValue('carriers/'.$carrierCode.'/title');
            }
    
            $methods[] = array('value' => $options, 'label' => $carrierTitle);
        }
    
        return $methods;    
    } 

    public function toOptionArray()
    {
        return $this->getShippingMethods();
    }
}

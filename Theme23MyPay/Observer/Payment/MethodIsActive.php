<?php

namespace Perspective\Theme23MyPay\Observer\Payment;

use Magento\Framework\Event\ObserverInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Perspective\Theme23MyPay\Helper\Data;

class MethodIsActive implements ObserverInterface
{
    protected $_cart;
    protected $_checkoutSession;
    protected $productRepository;
    protected $helper;

    public function __construct(
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Checkout\Model\Session $checkoutSession,
        ProductRepositoryInterface $productRepository,
        Data $helper,
    )
    {
        $this->_cart = $cart;
        $this->_checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->helper = $helper;
    }
    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $this->getCheckoutSession()->getQuote();
        $categoryID = $this->getProductCategories(); //Add your category ID#
        $categoryIdNumber = explode(",", $categoryID);
        $items = $quote->getAllItems();
        $flag = false;
        foreach($items as $item) {
            $product = $this->getProduct($item->getProductId());
            $categoryIds = $product->getCategoryIds();
            foreach($categoryIds as $catID){
                if(in_array($catID, $categoryIdNumber)){
                $flag = true; 
                }
                else {
                $flag = false;
                break;
                }
            }
            if ($flag == false){
                break;
            }
        }

        if($flag == false && $observer->getEvent()->getMethodInstance()->getCode()=="custompayment"){
            $checkResult = $observer->getEvent()->getResult();
            $checkResult->setData('is_available', false); 
        }
    }
    public function getProduct($productId)
    {
        return $product = $this->productRepository->getById($productId);
    }
    public function getCart()
    {        
        return $this->_cart;
    }

    public function getCheckoutSession()
    {
        return $this->_checkoutSession;
    }

    /**
     * Get selected options from menu configuration
     */
    public function getProductCategories()
    {
        return $this->helper->getProductCategories();
    }
}
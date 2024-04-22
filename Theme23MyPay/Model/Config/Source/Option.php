<?php

namespace Perspective\Theme23MyPay\Model\Config\Source;

class Option implements \Magento\Framework\Data\OptionSourceInterface
{
    protected $_storeManager;
    protected $categoryCollectionFactory;
    
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
    ) {
        $this->_storeManager = $storeManager;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    public function toOptionArray() {
        $array = [];
        $categories = $this->categoryCollectionFactory->create()                              
        ->addAttributeToSelect('*')
        ->setStore($this->_storeManager->getStore());
        foreach ($categories as $category){
            $array[] = ['value' => $category->getId(), 'label' => __($category->getName())];
        }

        return $array;
    }
}

<?php

namespace Perspective\Theme23MyPay\Setup;

use Magento\Customer\Api\Data\GroupInterfaceFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Customer\Api\Data\GroupInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Psr\Log\LoggerInterface;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Customer\Model\GroupFactory;

class UpgradeData implements UpgradeDataInterface
{
    const GROUP_CODE = 'General';

    const CUSTOM_GROUP_CODE = 'Large wholesale';

    const CUSTOM_GROUP_CODE_2 = 'Wholesale 2';

    /**
     * @var GroupInterfaceFactory
     */
    private $groupInterfaceFactory;

    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * @var State;
     */
    protected $state;
    
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var GroupFactory
     */
    protected $groupFactory;

    /**
     * Intialize Dependencies
     *
     * @param GroupInterfaceFactory $groupInterfaceFactory
     * @param GroupRepositoryInterface $groupRepository
     * @param State $state
     * @param LoggerInterface $logger
     * @param CollectionFactory $collectionFactory
     * @param GroupFactory $groupFactory
     * @return void
     */
    public function __construct(
        GroupInterfaceFactory $groupInterfaceFactory,
        GroupRepositoryInterface $groupRepository,
        State $state,
        LoggerInterface $logger,
        CollectionFactory $collectionFactory,
        GroupFactory $groupFactory
    ) {
        $this->groupInterfaceFactory    = $groupInterfaceFactory;
        $this->groupRepository          = $groupRepository;
        $this->state                    = $state;
        $this->logger                   = $logger;
        $this->collectionFactory        = $collectionFactory;
        $this->groupFactory             = $groupFactory;
    }
    /**
     * Function for upgrade data
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $group = $this->groupInterfaceFactory->create();

        $group
            ->setCode(self::CUSTOM_GROUP_CODE)
            ->setTaxClassId(\Magento\Customer\Model\ResourceModel\GroupRepository::DEFAULT_TAX_CLASS_ID);
        $this->groupRepository->save($group);

        $group
            ->setCode(self::CUSTOM_GROUP_CODE_2)
            ->setTaxClassId(\Magento\Customer\Model\ResourceModel\GroupRepository::DEFAULT_TAX_CLASS_ID);
        $this->groupRepository->save($group);

        /** assign group to customers */
        $this->assignGroupToCustomers();
        $installer->endSetup();
    }

    /**
     * Function for assign group to customers
     * @return void
     */
    public function assignGroupToCustomers()
    {
        // Set the current area to adminhtml to avoid security issues
        $this->state->setAreaCode(Area::AREA_ADMINHTML);
        // Get the custom group by code

        $customGroup = $this->groupFactory->create();

        $customGroupId = $customGroup->load(self::CUSTOM_GROUP_CODE, 'customer_group_code')->getId();
        $this->logger->info("Custom Group Id :: ".$customGroupId);

        $customGroupId2 = $customGroup->load(self::CUSTOM_GROUP_CODE_2, 'customer_group_code')->getId();
        $this->logger->info("Custom Group Id :: ".$customGroupId2);

        // Get all general customers
        $group = $this->groupFactory->create();
        $groupId = $group->load(self::GROUP_CODE, 'customer_group_code')->getId();
        $customerCollection = $this->collectionFactory->create();
        $customerCollection->addFieldToFilter('group_id', $groupId);
        foreach ($customerCollection as $customer) {
            // Assign the custom group to the customer
            $customer->setGroupId($customGroupId);
            $customer->save();
        }
    }
}
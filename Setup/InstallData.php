<?php

namespace OuterEdge\LeadSources\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Quote\Setup\QuoteSetupFactory;
use Magento\Sales\Setup\SalesSetupFactory;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var QuoteSetupFactory
     */
    protected $quoteSetupFactory;

    /**
     * @var SalesSetupFactory
     */
    protected $salesSetupFactory;
    
      /**
     * @param QuoteSetupFactory $quoteSetupFactory
     * @param SalesSetupFactory $salesSetupFactory
     */
    public function __construct(
        QuoteSetupFactory $quoteSetupFactory,
        SalesSetupFactory $salesSetupFactory
    ) {
        $this->quoteSetupFactory = $quoteSetupFactory;
        $this->salesSetupFactory = $salesSetupFactory;
    }
    
   /**
     * Upgrades 
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var \Magento\Quote\Setup\QuoteSetup $quoteInstaller */
        $quoteInstaller = $this->quoteSetupFactory->create(['resourceName' => 'quote_setup', 'setup' => $setup]);

        /** @var \Magento\Sales\Setup\SalesSetup $salesInstaller */
        $salesInstaller = $this->salesSetupFactory->create(['resourceName' => 'sales_setup', 'setup' => $setup]);

        $setup->startSetup();

        //Add attributes to quote 
        $entityAttributesCodes = [
            'lead' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT
        ];

        foreach ($entityAttributesCodes as $code => $type) {
            $quoteInstaller->addAttribute('quote', $code, ['type' => $type, 'length'=> 255, 'visible' => false, 'nullable' => true,]);
            $salesInstaller->addAttribute('order', $code, ['type' => $type, 'length'=> 255, 'visible' => false,'nullable' => true,]);
            $salesInstaller->addAttribute('invoice', $code, ['type' => $type, 'length'=> 255, 'visible' => false, 'nullable' => true,]);
        }

        $setup->endSetup();
    }
}

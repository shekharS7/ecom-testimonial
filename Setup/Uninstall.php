<?php
namespace RicherIndex\Testimonial\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;

class Uninstall implements UninstallInterface
{
    /**
     * Uninstall data and schema.
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        // Add the table name that you want to drop
        $tableName = $setup->getTable('richerindex_testimonial');

        // Check if the table exists and then drop it
        if ($setup->getConnection()->isTableExists($tableName)) {
            $setup->getConnection()->dropTable($tableName);
        }

        $setup->endSetup();
    }
}

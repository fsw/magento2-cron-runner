<?php

namespace Fsw\CronRunner\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $tableName = $setup->getTable('fsw_cron');

        $table = $setup->getConnection()->newTable($tableName);

        $table->addColumn('id', Table::TYPE_INTEGER, null, [
                'identity' => true, 'nullable' => false, 'primary'  => true, 'unsigned' => true,
            ])
            ->addColumn('group_id', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('job_name', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('status', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('pid', Table::TYPE_INTEGER, 10, ['nullable' => true])
            ->addColumn('return_code', Table::TYPE_INTEGER, 10, ['nullable' => true])
            ->addColumn('started_at', Table::TYPE_TIMESTAMP, null, ['nullable' => true])
            ->addColumn('finished_at', Table::TYPE_TIMESTAMP, null, ['nullable' => true])
            ->addColumn('error', Table::TYPE_TEXT, null, ['nullable' => true])
            ->addColumn('force_run_flag', Table::TYPE_BOOLEAN, 10, ['default' => false, 'nullable' => false])
            ->addColumn('stats_started', Table::TYPE_INTEGER, 10, ['default' => 0, 'nullable' => false])
            ->addColumn('stats_finished_error', Table::TYPE_INTEGER, 10, ['default' => 0, 'nullable' => false])
            ->addColumn('stats_finished_ok', Table::TYPE_INTEGER, 10, ['default' => 0, 'nullable' => false])
            ->addColumn('stats_last_duration', Table::TYPE_FLOAT, 10, ['nullable' => false])
            ->addColumn('setting_enabled', Table::TYPE_BOOLEAN, 10, ['default' => true, 'nullable' => false])
            ->addColumn('setting_memorylimit', Table::TYPE_INTEGER, 10, ['default' => null, 'nullable' => true])
            ->addColumn('setting_timelimit', Table::TYPE_INTEGER, 10, ['default' => null, 'nullable' => true])
            ->addColumn('schedule', Table::TYPE_TEXT, 255, ['default' => '', 'nullable' => false])
            ->addColumn('output', Table::TYPE_TEXT, null, ['nullable' => true])
            ->addColumn('stats_avg_duration', Table::TYPE_FLOAT, 10, ['nullable' => false])
            ->addColumn('stats_last_memory', Table::TYPE_FLOAT, 10, ['nullable' => false])
            ->addColumn('stats_avg_memory', Table::TYPE_FLOAT, 10, ['nullable' => false]);

        $setup->getConnection()->createTable($table);
        $setup->getConnection()->addIndex(
            $tableName,
            $setup->getIdxName(
                $tableName,
                ['group_id', 'job_name'],
                AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['group_id', 'job_name'],
            AdapterInterface::INDEX_TYPE_UNIQUE
        );
        $setup->endSetup();
    }

}
<?php

namespace AddOnInstaller;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

    public function installStep1()
    {
        $sm = $this->schemaManager();

        foreach ($this->getTables() as $tableName => $callback)
        {
            $sm->createTable($tableName, $callback);
            $sm->alterTable($tableName, $callback);
        }
    }

    public function upgrade2000000Step1()
    {
        $this->installStep1();
    }

    public function uninstallStep1()
    {
        $sm = $this->schemaManager();

        foreach ($this->getTables() as $tableName => $callback)
        {
            $sm->dropTable($tableName);
        }
    }

    /**
     * @return array
     */
    protected function getTables()
    {
        $tables = [];

        $tables['xf_addon_update_check'] = function ($table) {
            /** @var Create|Alter $table */
            $this->addOrChangeColumn($table, 'addon_id', 'varbinary', 50);
            $this->addOrChangeColumn($table, 'update_url', 'varchar', 250);
            $this->addOrChangeColumn($table, 'check_updates', 'int', 3)->setDefault(1);
            $this->addOrChangeColumn($table, 'last_checked', 'int', 10)->setDefault(0);
            $this->addOrChangeColumn($table, 'latest_version', 'varchar', 30);
            $this->addOrChangeColumn($table, 'skip_version', 'varchar', 30);
            $table->addPrimaryKey('addon_id');
        };

        return $tables;
    }

    /**
     * @param Create|Alter $table
     * @param string       $name
     * @param string|null  $type
     * @param string|null  $length
     * @return \XF\Db\Schema\Column
     */
    protected function addOrChangeColumn($table, $name, $type = null, $length = null)
    {
        if ($table instanceof Create)
        {
            $table->checkExists(true);

            return $table->addColumn($name, $type, $length);
        }
        else if ($table instanceof Alter)
        {
            if ($table->getColumnDefinition($name))
            {
                return $table->changeColumn($name, $type, $length);
            }

            return $table->addColumn($name, $type, $length);
        }
        else
        {
            throw new \LogicException("Unknown schema DDL type ". get_class($table));

        }
    }
}
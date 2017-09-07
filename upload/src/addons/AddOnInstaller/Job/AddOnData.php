<?php

namespace AddOnInstaller\Job;

use AddOnInstaller\CliRunnerShim;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use XF\Job\AbstractJob;

use XF\Cli\App;

class AddOnData extends AbstractJob
{
	protected $defaultData = [
		'addon_id' => null,
		'importer' => null,
		'status' => null,
	];

	/** @var  CliRunnerShim $runner */
	protected $runner;

	public function run($maxRunTime)
	{
		if ($this->data['addon_id'] == 'XF')
        {
            return $this->complete();
        }
        $addOnManager = $this->app->addOnManager();
		/** @var \XF\AddOn\AddOn $addOn */
        $addOn = $addOnManager->getById($this->data['addon_id']);
        if (!$addOn)
        {
            // add-on isn't installed, can't do anything
            return $this->complete();
        }

        $this->runner = $runner = new CliRunnerShim();
        $runner->setupShim();
        // copied from XF\Cli\Command\Development\Import

        // phrases go first as certain things (like templates) depend on the new values
        $importers = [
            'xf-dev:import-phrases',
            'xf-dev:import-admin-navigation',
            'xf-dev:import-admin-permissions',
            'xf-dev:import-advertising-positions',
            'xf-dev:import-bb-codes',
            'xf-dev:import-bb-code-media-sites',
            'xf-dev:import-class-extensions',
            'xf-dev:import-code-events',
            'xf-dev:import-code-event-listeners',
            'xf-dev:import-content-types',
            'xf-dev:import-cron-entries',
            'xf-dev:import-help-pages',
            'xf-dev:import-member-stats',
            'xf-dev:import-navigation',
            'xf-dev:import-options',
            'xf-dev:import-permissions',
            'xf-dev:import-routes',
            'xf-dev:import-style-properties',
            'xf-dev:import-template-modifications',
            'xf-dev:import-templates',
            'xf-dev:import-widget-definitions',
            'xf-dev:import-widget-positions'
        ];

        $addOnId = $this->data['addon_id'];

        $start = microtime(true);

        foreach ($importers AS $key => $importer)
        {
            if ($this->data['importer'] !== null && $key <= $this->data['importer'])
            {
                continue;
            }
            $this->data['importer'] = $key;

            $command = $runner->console->find($importer);

            $i = ['command' => $importer, '--addon' => $addOnId];

            $childInput = new ArrayInput($i);
            $command->run($childInput, $runner->output);
            $runner->output->writeln("");

            // keep the memory limit down on long running jobs
            \XF::em()->clearEntityCache();

            if ($maxRunTime && $start > $maxRunTime)
            {
                $this->data['status'] =  $this->runner->output->fetch();
                return $this->resume();
            }
        }

        $command = $runner->console->find('xf-dev:rebuild-caches');
        $childInput = new ArrayInput(['command' => 'xf-dev:rebuild-caches']);
        $command->run($childInput, $runner->output);
        $runner->output->writeln("");

        $total = microtime(true) - $start;
        $runner->output->writeln(sprintf("All data imported. (%.02fs)", $total));

        $addOn->postDataImport();

        return $this->complete();
	}

	public function getStatusMessage()
	{
        return isset($this->data['status']) ? $this->data['status'] : '';
	}

	public function canCancel()
	{
		return false;
	}

	public function canTriggerByChoice()
	{
		return false;
	}
}

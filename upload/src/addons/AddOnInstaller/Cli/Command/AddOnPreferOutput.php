<?php


namespace AddOnInstaller\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use XF\AddOn\AddOn;

trait AddOnPreferOutput
{
    protected function importAddOnData(InputInterface $input, OutputInterface $output, AddOn $addOn)
    {
        /** @var Command $this */
        $app = \XF::app();

        $devOutput = $app->developmentOutput();

        $importFromOutput = false;
        if ($devOutput->isAddOnOutputAvailable($addOn->addon_id))
        {
            if (\XF::options()->addonInstaller_auto_import_output)
            {
                $output->writeln(["", "Automatically importing _output"]);
                $importFromOutput = true;
            }
            else
            {
                /** @var QuestionHelper $helper */
                $helper = $this->getHelper('question');

                $question = new ConfirmationQuestion('<question>' . \XF::phrase('development_output_is_available_import_data_from_output_directory') .  '(y/n)</question>');
                if ($helper->ask($input, $output, $question))
                {
                    $importFromOutput = true;
                }
            }
        }

        if ($importFromOutput)
        {
            $command = $this->getApplication()->find('xf-dev:import');
            $childInput = new ArrayInput([
                                             'command' => 'xf-dev:import',
                                             '--addon' => $addOn->addon_id
                                         ]);
            $command->run($childInput, $output);

            $addOn->postDataImport();
        }
        else
        {
            $output->writeln(["", "Importing add-on data"]);
            $this->setupAndRunJob(
                'xfAddOnData-' . $addOn->addon_id,
                'XF:AddOnData',
                ['addon_id' => $addOn->addon_id],
                $output
            );

            // the job will trigger the post data import
        }
    }
}

<?php

namespace AddOnInstaller;

use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use XF\Cli\Runner;
use XF\Container;

class CliRunnerShim extends Runner
{
    /** @var ConsoleApplication */
    public $console;

    /** @var \XF\Cli\App */
    public $app;

    /** @var InputInterface */
    public $input;

    /** @var BufferedOutput */
    public $output;

    /**
     * @param array $input
     * @param string $appClass
     */
    public function setupShim(array $input = array(), $appClass = 'XF\Cli\App')
    {
        // based off setup from Runner::run
        $console = new ConsoleApplication();

        $this->registerCommands($console);

        $this->input = new ArrayInput($input);
        $this->output = new BufferedOutput();
        $this->output->getFormatter()->setStyle('warning', new OutputFormatterStyle('black', 'yellow'));

        $console->setCatchExceptions(false);
        $console->setAutoExit(false);

        /** @var \XF\Cli\App $app*/
        $app = new $appClass(new Container());
        $app->setup();
        $app->start();

        $this->app = $app;
        $this->console = $console;
    }
}

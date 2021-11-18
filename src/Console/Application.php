<?php

namespace Sdk\Console;

use Sdk\Facade;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class Application extends SymfonyApplication
{
    /**
     * @var \Sdk\Facade
     */
    protected $facade;

    /**
     * {@inheritDoc}
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $this->setAutoExit(false);
        $this->setCatchExceptions(false);

        if ($input === null) {
            $input = new ArgvInput();
        }

        if ($output === null) {
            $output = new ConsoleOutput();
        }

        try {
            $exitCode = parent::run($input, $output);

            $this->getFacade()->log(
                $this->getFacade()->mapExecutionToTaskLog($input)
            );
        } catch (Throwable $throwable) {
            $this->getFacade()->log(
                $this->getFacade()->mapExceptionToTaskLog($throwable, $input)
            );

            $exitCode = $throwable->getCode();

            $this->renderThrowable($throwable, $output);
        }

        if ($exitCode > 255) {
            $exitCode = 255;
        }

        return $exitCode;
    }

    /**
     * @return \Sdk\Facade
     */
    protected function getFacade(): Facade
    {
        if (!$this->facade) {
            $this->facade = new Facade();
        }

        return $this->facade;
    }
}

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

    public function __construct(string $name = 'UNKNOWN', string $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);
        $this->setCatchExceptions(false);
    }

    /**
     * {@inheritDoc}
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        if ($input === null) {
            $input = new ArgvInput();
        }

        if ($output === null) {
            $output = new ConsoleOutput();
        }

        try {
            $this->getFacade()->log(
                $this->getFacade()->mapExecutionToTaskLog($input)
            );

            $exitCode = parent::run($input, $output);

            return $exitCode;
        } catch (Throwable $throwable) {
            $this->getFacade()->log(
               $this->getFacade()->mapExceptionToTaskLog($throwable, $input)
            );
        }
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

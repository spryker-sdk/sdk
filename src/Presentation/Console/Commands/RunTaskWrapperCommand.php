<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RunTaskWrapperCommand extends Command
{
    /**
     * @var string
     */
    public const OPTION_TAGS = 'tags';

    /**
     * @var string
     */
    public const OPTION_STAGES = 'stages';

    /**
     * @var string
     */
    public const OPTION_SERIALIZE_CONTEXT = 'serialize-context';

    /**
     * @var string
     */
    public const OPTION_CONTEXT = 'context';

    protected TaskExecutor $taskExecutor;

    protected array $taskOptions;

    protected string $description;

    protected string $name;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor $taskExecutor
     * @param array<\Symfony\Component\Console\Input\InputOption> $taskOptions
     * @param string $description
     * @param string $name
     */
    public function __construct(
        TaskExecutor $taskExecutor,
        array $taskOptions,
        string $description,
        string $name
    ) {
        $this->description = $description;
        $this->taskOptions = $taskOptions;
        $this->taskExecutor = $taskExecutor;
        $this->name = $name;
        parent::__construct($name);
    }

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this->setDescription($this->description);

        foreach ($this->taskOptions as $taskOption) {
            $mode = $taskOption->isValueOptional() ? InputOption::VALUE_OPTIONAL : InputOption::VALUE_REQUIRED;
            if ($taskOption->isArray()) {
                $mode = $mode | InputOption::VALUE_IS_ARRAY;
            }
            $this->addOption(
                $taskOption->getName(),
                null,
                $mode,
                $taskOption->getDescription(),
                $taskOption->getDefault(),
            );
        }
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        $context = $this->buildContext($input);

        $context = $this->taskExecutor->execute($this->name, $context);

        if ($input->hasOption(static::OPTION_SERIALIZE_CONTEXT) && $input->getOption(static::OPTION_SERIALIZE_CONTEXT)) {
            $output->writeln(json_encode($context, JSON_THROW_ON_ERROR));
        } else {
            $this->writeFilteredMessages($output, $context);
        }

        return $context->getResult();
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Context $context
     *
     * @return void
     */
    protected function writeFilteredMessages(
        OutputInterface $output,
        Context $context
    ): void {
        $verbosity = $this->getVerbosity($output);

        foreach ($context->getMessages() as $message) {
            if ($message->getVerbosity() <= $verbosity) {
                $output->writeln($this->formatMessage($message));
            }
        }
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Message $message
     *
     * @return string
     */
    protected function formatMessage(Message $message): string
    {
        return match ($message->getVerbosity()) {
            Message::INFO => '<info>Info: ' . $message->getMessage() . '</info>',
            Message::ERROR => '<error>Error: ' . $message->getMessage() . '</error>',
            Message::SUCCESS => '<fg=black;bg=green>Success: ' . $message->getMessage() . '</>',
            Message::DEBUG => '<fg=black;bg=yellow>Debug: ' . $message->getMessage() . '</>',
            default => $message->getMessage(),
        };
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function getVerbosity(OutputInterface $output): int
    {
        if ($output->isVerbose()) {
            return Message::SUCCESS;
        }

        if ($output->isVeryVerbose()) {
            return Message::INFO;
        }

        if ($output->isDebug()) {
            return Message::DEBUG;
        }

        return Message::ERROR;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Context
     */
    protected function buildContext(InputInterface $input): Context
    {
        $context = new Context();

        if ($input->hasOption(static::OPTION_CONTEXT) && $input->getOption(static::OPTION_CONTEXT)) {
            $contextData = json_decode($input->getOption(static::OPTION_CONTEXT), true);
            $context->fromArray($contextData);

            return $context;
        }

        if ($input->hasOption(static::OPTION_TAGS)) {
            $context->setTags($input->getOption(static::OPTION_TAGS));
        }

        if ($input->hasOption(static::OPTION_STAGES)) {
            $context->setStages($input->getOption(static::OPTION_STAGES));
        }

        return $context;
    }
}

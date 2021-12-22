<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ContextRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Infrastructure\Repository\Violation\ReportFormatterFactory;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
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
    public const OPTION_WRITE_CONTEXT_TO = 'write-context-to';

    /**
     * @var string
     */
    public const OPTION_READ_CONTEXT_FROM = 'read-context-from';

    /**
     * @var string
     */
    public const OPTION_ENABLE_CONTEXT_WRITING = 'context-writing-enabled';

    /**
     * @var string
     */
    public const OPTION_DRY_RUN = 'dry-run';

    /**
     * @var string
     */
    public const OPTION_OVERWRITES = 'overwrites';

    /**
     * @var string
     */
    public const OPTION_FORMAT = 'format';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor
     */
    protected TaskExecutor $taskExecutor;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\Violation\ReportFormatterFactory
     */
    protected ReportFormatterFactory $reportFormatterFactory;

    /**
     * @var array<\Symfony\Component\Console\Input\InputOption>
     */
    protected array $taskOptions;

    /**
     * @var string
     */
    protected string $description;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ContextRepositoryInterface
     */
    protected ContextRepositoryInterface $contextRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor $taskExecutor
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ContextRepositoryInterface $contextRepository
     * @param \SprykerSdk\Sdk\Infrastructure\Repository\Violation\ReportFormatterFactory $reportFormatterFactory
     * @param array<\Symfony\Component\Console\Input\InputOption> $taskOptions
     * @param string $description
     * @param string $name
     */
    public function __construct(
        TaskExecutor $taskExecutor,
        ContextRepositoryInterface $contextRepository,
        ReportFormatterFactory $reportFormatterFactory,
        array $taskOptions,
        string $description,
        string $name
    ) {
        $this->description = $description;
        $this->taskOptions = $taskOptions;
        $this->taskExecutor = $taskExecutor;
        $this->reportFormatterFactory = $reportFormatterFactory;
        $this->name = $name;
        $this->contextRepository = $contextRepository;
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
        $this->writeContext($input, $context);
        $this->writeFilteredMessages($output, $context);

        return $context->getExitCode();
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    protected function writeFilteredMessages(
        OutputInterface $output,
        ContextInterface $context
    ): void {
        $verbosity = $this->getVerbosity($output);

        foreach ($context->getMessages() as $message) {
            if ($message->getVerbosity() <= $verbosity) {
                $output->writeln($this->formatMessage($message));
            }
        }
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\MessageInterface $message
     *
     * @return string
     */
    protected function formatMessage(MessageInterface $message): string
    {
        return match ($message->getVerbosity()) {
            MessageInterface::INFO => '<info>Info: ' . $message->getMessage() . '</info>',
            MessageInterface::ERROR => '<error>Error: ' . $message->getMessage() . '</error>',
            MessageInterface::SUCCESS => '<fg=black;bg=green>Success: ' . $message->getMessage() . '</>',
            MessageInterface::DEBUG => '<fg=black;bg=yellow>Debug: ' . $message->getMessage() . '</>',
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
            return MessageInterface::SUCCESS;
        }

        if ($output->isVeryVerbose()) {
            return MessageInterface::INFO;
        }

        if ($output->isDebug()) {
            return MessageInterface::DEBUG;
        }

        return MessageInterface::ERROR;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function buildContext(InputInterface $input): ContextInterface
    {
        $context = $this->createContext($input);

        if ($input->hasOption(static::OPTION_DRY_RUN)) {
            $context->setIsDryRun((bool)$input->getOption(static::OPTION_DRY_RUN));
        }

        if ($input->hasOption(static::OPTION_TAGS)) {
            $context->setTags($input->getOption(static::OPTION_TAGS));
        }

        if ($input->hasOption(static::OPTION_STAGES)) {
            $context->setInputStages($input->getOption(static::OPTION_STAGES));
        }

        if ($input->hasOption(static::OPTION_OVERWRITES) && !empty($input->getOption(static::OPTION_OVERWRITES))) {
            $context->setOverwrites($input->getOption(static::OPTION_OVERWRITES));
        }

        if ($input->hasOption(static::OPTION_FORMAT) && $input->getOption(static::OPTION_FORMAT)) {
            $this->reportFormatterFactory->setFormat($input->getOption(static::OPTION_FORMAT));
        }

        return $context;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    protected function writeContext(InputInterface $input, ContextInterface $context): void
    {
        if (
            $input->hasOption(static::OPTION_ENABLE_CONTEXT_WRITING)
            && $input->getOption(static::OPTION_ENABLE_CONTEXT_WRITING)
            && $input->hasOption(static::OPTION_WRITE_CONTEXT_TO)
            && $input->getOption(static::OPTION_WRITE_CONTEXT_TO)
        ) {
            $contextFilePath = $input->getOption(static::OPTION_WRITE_CONTEXT_TO);
            $context->setName($contextFilePath);
            $this->contextRepository->saveContext($context);
        }
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function createContext(InputInterface $input): ContextInterface
    {
        if (
            !$input->hasOption(static::OPTION_READ_CONTEXT_FROM)
            || !$input->getOption(static::OPTION_READ_CONTEXT_FROM)
        ) {
            return new Context();
        }

        $contextFilePath = $input->getOption(static::OPTION_READ_CONTEXT_FROM);

        return $this->contextRepository->findByName($contextFilePath) ?: new Context();
    }
}

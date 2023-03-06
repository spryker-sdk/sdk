<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command;

use RuntimeException;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestResponseDtoInterface;
use SprykerSdk\Sdk\Core\Application\Manifest\ManifestGeneratorInterface;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\ManifestInteractionProcessorInterface;
use SprykerSdk\Sdk\Presentation\Console\Manifest\Task\TaskInteractionMap;
use SprykerSdk\Sdk\Presentation\Console\Manifest\Task\TaskManifestRequestDtoFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateTaskCommand extends Command
{
    /**
     * @var string
     */
    protected const NAME = 'sdk:generate:task';

    /**
     * @var string
     */
    protected const DESCRIPTION = 'Task generator command';

    /**
     * @var string
     */
    protected const TASK_FORMAT_OPTION = 'task-format';

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\ManifestInteractionProcessorInterface
     */
    protected ManifestInteractionProcessorInterface $manifestInteractionProcessor;

    /**
     * @var \SprykerSdk\Sdk\Presentation\Console\Manifest\Task\TaskInteractionMap
     */
    protected TaskInteractionMap $taskInteractionMap;

    /**
     * @var \SprykerSdk\Sdk\Presentation\Console\Manifest\Task\TaskManifestRequestDtoFactory
     */
    protected TaskManifestRequestDtoFactory $taskManifestRequestDtoFactory;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Manifest\ManifestGeneratorInterface
     */
    protected ManifestGeneratorInterface $manifestGenerator;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\ManifestInteractionProcessorInterface $manifestInteractionProcessor
     * @param \SprykerSdk\Sdk\Presentation\Console\Manifest\Task\TaskInteractionMap $taskInteractionMap
     * @param \SprykerSdk\Sdk\Presentation\Console\Manifest\Task\TaskManifestRequestDtoFactory $taskManifestRequestDtoFactory
     * @param \SprykerSdk\Sdk\Core\Application\Manifest\ManifestGeneratorInterface $manifestGenerator
     */
    public function __construct(
        ManifestInteractionProcessorInterface $manifestInteractionProcessor,
        TaskInteractionMap $taskInteractionMap,
        TaskManifestRequestDtoFactory $taskManifestRequestDtoFactory,
        ManifestGeneratorInterface $manifestGenerator
    ) {
        parent::__construct(static::NAME);

        $this->manifestInteractionProcessor = $manifestInteractionProcessor;
        $this->taskInteractionMap = $taskInteractionMap;
        $this->taskManifestRequestDtoFactory = $taskManifestRequestDtoFactory;
        $this->manifestGenerator = $manifestGenerator;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $this->setDescription(static::DESCRIPTION);

        $this->addOption(
            static::TASK_FORMAT_OPTION,
            null,
            InputOption::VALUE_REQUIRED,
            'Task format',
            'yaml',
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $predefinedValue = [TaskInteractionMap::FILE_FORMAT_KEY => $input->getOption(static::TASK_FORMAT_OPTION)];

        $receivedValues = $this->manifestInteractionProcessor->receiveValues(
            $this->taskInteractionMap->getInteractionMap($predefinedValue, $output),
        );

        $taskManifestRequestDto = $this->taskManifestRequestDtoFactory->createFromReceivedValues($receivedValues);

        $response = $this->manifestGenerator->generate($taskManifestRequestDto);

        $this->updateTask($output);

        $this->writeSuccessMessage($response, $output);

        return static::SUCCESS;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function updateTask(OutputInterface $output): void
    {
        $output->writeln('');
        $output->writeln('Updating tasks...');

        $application = $this->getApplication();

        if ($application === null) {
            throw new RuntimeException('Can\'t find current console application');
        }

        $commandExecutionCode = $application
            ->find(UpdateSdkCommand::NAME)
            ->run(new ArrayInput([]), $output);

        if ($commandExecutionCode !== static::SUCCESS) {
            throw new RuntimeException('Task update failed. Try to update it manually');
        }
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestResponseDtoInterface $response
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function writeSuccessMessage(ManifestResponseDtoInterface $response, OutputInterface $output): void
    {
        $output->writeln('');
        $output->writeln(sprintf('Task generated file: <info>%s</info>', $response->getCreatedFileName()));
        $output->writeln('');
    }
}

<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Application\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Presentation\Console\Command\RunTaskWrapperCommand;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;
use Symfony\Component\Console\Input\InputOption;

class TaskOptionBuilder
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\PlaceholderResolver
     */
    protected PlaceholderResolver $placeholderResolver;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\PlaceholderResolver $placeholderResolver
     */
    public function __construct(PlaceholderResolver $placeholderResolver)
    {
        $this->placeholderResolver = $placeholderResolver;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return array<\Symfony\Component\Console\Input\InputOption>
     */
    public function extractOptions(TaskInterface $task): array
    {
        $options = [];
        $options = $this->addPlaceholderOptions($task, $options);
        $options = $this->addTagOptions($task, $options);
        $options = $this->addStageOptions($options);
        $options = $this->addContextOptions($options);

        return $options;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     * @param array<\Symfony\Component\Console\Input\InputOption> $options
     *
     * @return array<\Symfony\Component\Console\Input\InputOption>
     */
    protected function addTagOptions(TaskInterface $task, array $options): array
    {
        $tags = [];

        foreach ($task->getCommands() as $command) {
            $tags[] = $command->getTags();
        }
        $tags = array_merge(...$tags);

        if (count($tags) > 0) {
            $options[] = new InputOption(
                RunTaskWrapperCommand::OPTION_TAGS,
                substr(RunTaskWrapperCommand::OPTION_TAGS, 0, 1),
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                'Only execute subtasks that matches at least one of the given tags',
                array_values(array_unique($tags)),
            );
        }

        return $options;
    }

    /**
     * @param array<\Symfony\Component\Console\Input\InputOption> $options
     *
     * @return array<\Symfony\Component\Console\Input\InputOption>
     */
    protected function addStageOptions(array $options): array
    {
        $options[] = new InputOption(
            RunTaskWrapperCommand::OPTION_STAGES,
            substr(RunTaskWrapperCommand::OPTION_STAGES, 0, 1),
            InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
            'Only execute subtasks that matches at least one of the given stages',
            [],
        );

        return $options;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     * @param array<\Symfony\Component\Console\Input\InputOption> $options
     *
     * @return array<\Symfony\Component\Console\Input\InputOption>
     */
    protected function addPlaceholderOptions(TaskInterface $task, array $options): array
    {
        foreach ($task->getPlaceholders() as $placeholder) {
            $valueResolver = $this->placeholderResolver->getValueResolver($placeholder);

            if ($valueResolver->getAlias() === null) {
                continue;
            }

            $mode = $placeholder->isOptional() ? InputOption::VALUE_OPTIONAL : InputOption::VALUE_REQUIRED;
            if (
                isset($placeholder->getConfiguration()['type']) &&
                $placeholder->getConfiguration()['type'] === ValueTypeEnum::TYPE_ARRAY
            ) {
                $mode = $mode | InputOption::VALUE_IS_ARRAY;
            }

            $options[] = new InputOption(
                $valueResolver->getAlias(),
                null,
                $mode,
                $valueResolver->getDescription(),
            );
        }

        return $options;
    }

    /**
     * @param array<\Symfony\Component\Console\Input\InputOption> $options
     *
     * @return array<\Symfony\Component\Console\Input\InputOption>
     */
    protected function addContextOptions(array $options): array
    {
        $defaultContextFilePath = getcwd() . DIRECTORY_SEPARATOR . 'sdk.context.json';

        $options[] = new InputOption(
            RunTaskWrapperCommand::OPTION_READ_CONTEXT_FROM,
            null,
            InputOption::VALUE_OPTIONAL,
            'Read the context from given JSON file. Can be overwritten via additional options',
            null,
        );
        $options[] = new InputOption(
            RunTaskWrapperCommand::OPTION_ENABLE_CONTEXT_WRITING,
            null,
            InputOption::VALUE_OPTIONAL,
            'Enable serializing the context into a file',
            false,
        );
        $options[] = new InputOption(
            RunTaskWrapperCommand::OPTION_WRITE_CONTEXT_TO,
            null,
            InputOption::VALUE_OPTIONAL,
            'Current context will be written to the given filepath in JSON format',
            $defaultContextFilePath,
        );
        $options[] = new InputOption(
            RunTaskWrapperCommand::OPTION_DRY_RUN,
            'd',
            InputOption::VALUE_OPTIONAL,
            'Will only simulate a run and not execute any of the commands',
            false,
        );
        $options[] = new InputOption(
            RunTaskWrapperCommand::OPTION_OVERWRITES,
            'o',
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Will allow to overwrite values that are already passed inside the context',
            [],
        );
        $options[] = new InputOption(
            RunTaskWrapperCommand::OPTION_FORMAT,
            null,
            InputOption::VALUE_OPTIONAL,
            'Set format for violations report',
            null,
        );

        return $options;
    }
}

<?php

namespace Sdk\Logger\Mapper;

use Sdk\Logger\Task\EventEnum;
use Sdk\Logger\Task\TriggeredByEnum;
use Sdk\Logger\Task\TypeEnum;
use Sdk\Dto\TaskLogDto;
use Symfony\Component\Console\Input\InputInterface;
use Throwable;

class TaskLogMapper implements TaskLogMapperInterface
{
    protected const ARGUMENTS_SEPARATOR = ' ';

    /**
     * @param Throwable $throwable
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return \Sdk\Dto\TaskLogDto
     */
    public function mapExceptionToTaskLog(Throwable $throwable, InputInterface $input): TaskLogDto
    {
        return new TaskLogDto(
            (string) $input->getFirstArgument(),
            TypeEnum::TASK,
            EventEnum::ERROR,
            false,
            TriggeredByEnum::USER,
            implode(static::ARGUMENTS_SEPARATOR, $input->getArguments()),
            $throwable->getMessage()
        );
    }

    /**
     * @param InputInterface $input
     *
     * @return TaskLogDto
     */
    public function mapExecutionToTaskLog(InputInterface $input): TaskLogDto
    {
        return new TaskLogDto(
            (string) $input->getFirstArgument(),
            TypeEnum::TASK,
            EventEnum::EXECUTED,
            true,
            TriggeredByEnum::USER,
            implode(static::ARGUMENTS_SEPARATOR, $input->getArguments())
        );
    }
}

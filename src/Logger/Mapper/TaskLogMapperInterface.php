<?php

namespace Sdk\Logger\Mapper;

use Sdk\Dto\TaskLogDto;
use Symfony\Component\Console\Input\InputInterface;
use Throwable;

interface TaskLogMapperInterface
{
    /**
     * @param Throwable $throwable
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return \Sdk\Dto\TaskLogDto
     */
    public function mapExceptionToTaskLog(Throwable $throwable, InputInterface $input): TaskLogDto;

    /**
     * @param InputInterface $input
     *
     * @return \Sdk\Dto\TaskLogDto
     */
    public function mapExecutionToTaskLog(InputInterface $input): TaskLogDto;
}

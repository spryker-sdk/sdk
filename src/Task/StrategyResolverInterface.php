<?php

namespace Sdk\Task;

use Sdk\Task\TypeStrategy\TypeStrategyInterface;

interface StrategyResolverInterface
{
    /**
     * @param array $definition
     *
     * @throws \Sdk\Task\Exception\TaskTypeNotResolved
     *
     * @return \Sdk\Task\TypeStrategy\TypeStrategyInterface
     */
    public function resolve(array $definition): TypeStrategyInterface;
}

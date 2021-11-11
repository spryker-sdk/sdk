<?php

namespace Sdk\Task;

use Sdk\Task\Exception\TaskTypeNotResolved;
use Sdk\Task\TypeStrategy\TypeStrategyInterface;

class StrategyResolver implements StrategyResolverInterface
{
    /**
     * @var array|\Sdk\Task\TypeStrategy\TypeStrategyInterface[]
     */
    protected array $typeStrategies = [];

    /**
     * @param \Sdk\Task\TypeStrategy\TypeStrategyInterface[] $typeStrategies
     */
    public function __construct(array $typeStrategies)
    {
        $this->typeStrategies = $typeStrategies;
    }

    /**
     * @param array $definition
     *
     * @throws \Sdk\Task\Exception\TaskTypeNotResolved
     *
     * @return \Sdk\Task\TypeStrategy\TypeStrategyInterface
     */
    public function resolve(array $definition): TypeStrategyInterface
    {
        foreach ($this->typeStrategies as $typeStrategy) {
            if ($typeStrategy->getType() === $definition['type']) {
                return $typeStrategy->setDefinition($definition);
            }
        }

        throw new TaskTypeNotResolved(sprintf('Task `%s` with type `%s` is not supported', $definition['id'], $definition['type']));
    }
}

<?php

namespace Sdk\Task\ValueResolver;

interface ValueResolverInterface
{
    /**
     * @param array $definition
     *
     * @throws \Sdk\Task\Exception\ValueResolverNotResolved
     *
     * @return array
     */
    public function expand(array $definition): array;
}

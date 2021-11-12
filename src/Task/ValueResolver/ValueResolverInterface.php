<?php

namespace Sdk\Task\ValueResolver;

interface ValueResolverInterface
{
    /**
     * @param array $placeholders
     * @param bool $resolveValue
     *
     * @throws \Sdk\Task\Exception\ValueResolverNotResolved
     *
     * @return array
     */
    public function expand(array $placeholders, bool $resolveValue = false): array;
}

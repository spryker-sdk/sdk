<?php

namespace Sdk\Task\Executor;

use Sdk\Style\StyleInterface;

interface TaskExecutorInterface
{
    /**
     * @param array $options
     * @param \Sdk\Style\StyleInterface $style
     *
     * @return void
     */
    public function execute(array $options, StyleInterface $style): void;
}

<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event\Profiler;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Filesystem\Filesystem;

class ProfilerEventListener
{
    /**
     * @var bool
     */
    protected bool $isProfilerEnabled;

    /**
     * @var string
     */
    protected string $profilerDir;

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @param bool $isProfilerEnabled
     * @param string $profilerDir
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     */
    public function __construct(
        bool $isProfilerEnabled,
        string $profilerDir,
        Filesystem $filesystem
    ) {
        $this->isProfilerEnabled = $isProfilerEnabled;
        $this->profilerDir = rtrim($profilerDir, DIRECTORY_SEPARATOR);
        $this->filesystem = $filesystem;
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
     *
     * @return void
     */
    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        if (!$this->isProfilerEnabled()) {
            return;
        }

        xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    public function onConsoleTerminate(ConsoleTerminateEvent $event): void
    {
        if (!$this->isProfilerEnabled()) {
            return;
        }

        $profilerData = xhprof_disable();

        $this->filesystem->dumpFile(
            $this->profilerDir . DIRECTORY_SEPARATOR . $this->getProfilerName($event),
            serialize($profilerData),
        );
    }

    /**
     * @return bool
     */
    protected function isProfilerEnabled(): bool
    {
        return $this->isProfilerEnabled && function_exists('xhprof_enable');
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return string
     */
    protected function getProfilerName(ConsoleTerminateEvent $event): string
    {
        $requestId = bin2hex(random_bytes(5));
        $command = 'command';

        if ($event->getCommand() !== null && $event->getCommand()->getName() !== null) {
            $command = preg_replace('/\W/', '_', $event->getCommand()->getName());
        }

        return sprintf('%s.%s.xhprof', $requestId, $command);
    }
}

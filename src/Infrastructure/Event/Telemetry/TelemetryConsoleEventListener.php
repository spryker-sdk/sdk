<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event\Telemetry;

use Psr\Log\LoggerInterface;
use SprykerSdk\Sdk\Core\Application\Telemetry\TelemetryEventMetadataFactoryInterface;
use SprykerSdk\Sdk\Core\Application\Telemetry\TelemetryEventsSynchronizerInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\Payload\CommandExecutionPayload;
use SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\TelemetryEvent;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Throwable;

class TelemetryConsoleEventListener
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Telemetry\TelemetryEventsSynchronizerInterface
     */
    protected TelemetryEventsSynchronizerInterface $telemetryEventsSynchronizer;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Telemetry\TelemetryEventMetadataFactoryInterface
     */
    protected TelemetryEventMetadataFactoryInterface $telemetryEventMetadataFactory;

    /**
     * @var bool
     */
    protected bool $isTelemetryEnabled;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Event\Telemetry\TelemetryConsoleEventValidatorInterface
     */
    protected TelemetryConsoleEventValidatorInterface $telemetryConsoleEventValidator;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Telemetry\TelemetryEventsSynchronizerInterface $telemetryEventsSynchronizer
     * @param \SprykerSdk\Sdk\Core\Application\Telemetry\TelemetryEventMetadataFactoryInterface $telemetryEventMetadataFactory
     * @param \SprykerSdk\Sdk\Infrastructure\Event\Telemetry\TelemetryConsoleEventValidatorInterface $telemetryConsoleEventValidator
     * @param \Psr\Log\LoggerInterface $logger
     * @param bool $isTelemetryEnabled
     */
    public function __construct(
        TelemetryEventsSynchronizerInterface $telemetryEventsSynchronizer,
        TelemetryEventMetadataFactoryInterface $telemetryEventMetadataFactory,
        TelemetryConsoleEventValidatorInterface $telemetryConsoleEventValidator,
        LoggerInterface $logger,
        bool $isTelemetryEnabled
    ) {
        $this->telemetryEventsSynchronizer = $telemetryEventsSynchronizer;
        $this->telemetryEventMetadataFactory = $telemetryEventMetadataFactory;
        $this->telemetryConsoleEventValidator = $telemetryConsoleEventValidator;
        $this->logger = $logger;
        $this->isTelemetryEnabled = $isTelemetryEnabled;
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    public function onConsoleTerminate(ConsoleTerminateEvent $event): void
    {
        if (!$this->isApplicable($event)) {
            return;
        }

        $this->addSuccessfulCommandEvent($event);

        $this->synchronizeEvents($event);
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleErrorEvent $event
     *
     * @return void
     */
    public function onConsoleError(ConsoleErrorEvent $event): void
    {
        if (!$this->isApplicable($event)) {
            return;
        }

        $this->addFailedCommandEvent($event);
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleEvent $event
     *
     * @return bool
     */
    protected function isApplicable(ConsoleEvent $event): bool
    {
        return $this->isTelemetryEnabled && $this->telemetryConsoleEventValidator->isValid($event);
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    protected function addSuccessfulCommandEvent(ConsoleTerminateEvent $event): void
    {
        if ($event->getCommand() === null || $event->getCommand()->getName() === null) {
            return;
        }

        $telemetryEvent = new TelemetryEvent(new CommandExecutionPayload(
            $event->getCommand()->getName(),
            $event->getInput()->getArguments(),
            $event->getInput()->getOptions(),
            '',
            $event->getExitCode(),
        ), $this->telemetryEventMetadataFactory->createTelemetryEventMetadata());

        $this->telemetryEventsSynchronizer->persist($telemetryEvent);
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleErrorEvent $event
     *
     * @return void
     */
    protected function addFailedCommandEvent(ConsoleErrorEvent $event): void
    {
        if ($event->getCommand() === null || $event->getCommand()->getName() === null) {
            return;
        }

        $telemetryEvent = new TelemetryEvent(new CommandExecutionPayload(
            $event->getCommand()->getName(),
            $event->getInput()->getArguments(),
            $event->getInput()->getOptions(),
            $event->getError()->getMessage(),
            $event->getExitCode(),
        ), $this->telemetryEventMetadataFactory->createTelemetryEventMetadata());

        $this->telemetryEventsSynchronizer->persist($telemetryEvent);
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    protected function synchronizeEvents(ConsoleTerminateEvent $event): void
    {
        if ($event->getOutput()->isDebug()) {
            $event->getOutput()->writeln('<info>Telemetry events synchronization...</info>');
        }

        try {
            $this->telemetryEventsSynchronizer->synchronize();
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());
            $event->getOutput()->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }

        if ($event->getOutput()->isDebug()) {
            $event->getOutput()->writeln('<info>Telemetry events synchronization finished</info>');
        }
    }
}

<?php

namespace EvaluatorExtension\Task;

use EvaluatorExtension\Task\Command\EvaluateCommand;
use SprykerSdk\Evaluator\Executor\EvaluatorExecutorInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class
EvaluateTask implements TaskInterface
{
    protected EvaluatorExecutorInterface $evaluatorExecutor;

    /**
     * @param EvaluatorExecutorInterface $evaluatorExecutor
     */
    public function __construct(EvaluatorExecutorInterface $evaluatorExecutor)
    {
        $this->evaluatorExecutor = $evaluatorExecutor;
    }

    public function getId(): string
    {
        return 'evaluator:run';
    }

    public function getShortDescription(): string
    {
        return '';
    }

    public function getCommands(): array
    {
        return [
            new EvaluateCommand($this->evaluatorExecutor)
        ];
    }

    public function getPlaceholders(): array
    {
        return [];
    }

    public function getHelp(): ?string
    {
        return null;
    }

    public function getVersion(): string
    {
        return '0.1.0';
    }

    public function isDeprecated(): bool
    {
        return false;
    }

    public function isOptional(): bool
    {
        return false;
    }

    public function getSuccessor(): ?string
    {
        return null;
    }

    public function getLifecycle(): LifecycleInterface
    {
        return new Lifecycle(
            new InitializedEventData(),
            new UpdatedEventData(),
            new RemovedEventData(),
        );
    }

    public function getStages(): array
    {
        return [];
    }
}

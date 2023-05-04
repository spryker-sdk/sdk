<?php

namespace EvaluatorExtension\Task\Command;

use SprykerSdk\Evaluator\Dto\EvaluatorInputDataDto;
use SprykerSdk\Evaluator\Executor\EvaluatorExecutorInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;

class
EvaluateCommand implements ExecutableCommandInterface
{

    protected EvaluatorExecutorInterface $evaluatorExecutor;

    /**
     * @param EvaluatorExecutorInterface $evaluatorExecutor
     */
    public function __construct(EvaluatorExecutorInterface $evaluatorExecutor)
    {
        $this->evaluatorExecutor = $evaluatorExecutor;
    }

    public function getCommand(): string
    {
        return static::class;
    }

    public function getType(): string
    {
        return 'php';
    }

    public function getTags(): array
    {
        return [];
    }

    public function hasStopOnError(): bool
    {
        return true;
    }

    public function getConverter(): ?ConverterInterface
    {
        return null;
    }

    public function getStage(): string
    {
        return ContextInterface::DEFAULT_STAGE;
    }

    public function execute(ContextInterface $context): ContextInterface
    {
        $dto = new EvaluatorInputDataDto('/project');
        $response = $this->evaluatorExecutor->execute($dto);
        if ($response->isSuccessful()) {
            $context->setExitCode(0);

            return $context;
        }

        foreach ($response->getReportLines() as $reportLines) {
            foreach ($reportLines->getViolations() as $key => $violation) {
                $context->addMessage($reportLines->getCheckerName() . $key,
                    new Message($violation->getMessage(), MessageInterface::ERROR)
                );
            }
            if ($reportLines->getViolations() && $reportLines->getDocUrl() !== '') {
                $context->addMessage(
                    $reportLines->getCheckerName() . '__doclink__',
                    new Message($reportLines->getDocUrl(), MessageInterface::ERROR)
                );
            }
        }
        return $context;
    }
}

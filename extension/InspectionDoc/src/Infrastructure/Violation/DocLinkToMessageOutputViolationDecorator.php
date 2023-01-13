<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace InspectionDoc\Infrastructure\Violation;

use InspectionDoc\Infrastructure\Repository\InspectionDocRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dto\Violation\Violation;
use SprykerSdk\Sdk\Infrastructure\Violation\Formatter\OutputViolationDecoratorInterface;
use SprykerSdk\SdkContracts\Report\Violation\ViolationInterface;

class DocLinkToMessageOutputViolationDecorator implements OutputViolationDecoratorInterface
{
    /**
     * @var \InspectionDoc\Infrastructure\Repository\InspectionDocRepositoryInterface
     */
    protected InspectionDocRepositoryInterface $inspectionDocRepository;

    /**
     * @var string
     */
    protected string $inspectionDocUrl;

    /**
     * @param \InspectionDoc\Infrastructure\Repository\InspectionDocRepositoryInterface $inspectionDocRepository
     * @param string $inspectionDocUrl
     */
    public function __construct(InspectionDocRepositoryInterface $inspectionDocRepository, string $inspectionDocUrl)
    {
        $this->inspectionDocRepository = $inspectionDocRepository;
        $this->inspectionDocUrl = $inspectionDocUrl;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Report\Violation\ViolationInterface $violation
     *
     * @return \SprykerSdk\SdkContracts\Report\Violation\ViolationInterface
     */
    public function decorate(ViolationInterface $violation): ViolationInterface
    {
        $newViolation = new Violation(
            $violation->getId(),
            $this->decorateMessage($violation->getMessage(), $violation->getAdditionalAttributes()),
        );

        $newViolation->setClass($violation->getClass())
            ->setMethod($violation->getMethod())
            ->setAttributes($violation->getAdditionalAttributes())
            ->setFix($violation->getFix())
            ->setFixable($violation->isFixable())
            ->setProduced($violation->producedBy())
            ->setStartColumn($violation->getStartColumn())
            ->setEndColumn($violation->getEndColumn())
            ->setStartLine($violation->getStartLine())
            ->setEndLine($violation->getEndLine())
            ->setPriority($violation->priority())
            ->setSeverity($violation->getSeverity());

        return $newViolation;
    }

    /**
     * @param string $message
     * @param array $attributes
     *
     * @return string
     */
    protected function decorateMessage(string $message, array $attributes): string
    {
        if (!isset($attributes['inspectionId'])) {
            return $message;
        }

        $inspectionId = trim($attributes['inspectionId']);

        if ($inspectionId === '') {
            return $message;
        }

        $inspectionDoc = $this->inspectionDocRepository->findByErrorCode($inspectionId);

        if ($inspectionDoc === null) {
            return $message;
        }

        $link = trim($inspectionDoc->getLink());

        if ($link === '') {
            return $message;
        }

        return $message . PHP_EOL . 'More information: ' . $this->inspectionDocUrl . $link;
    }
}

<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Exception\InvalidReportTypeException;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use SprykerSdk\SdkContracts\Report\ReportInterface;

class ContextSerializer
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\ReportArrayConverterFactory
     */
    private ReportArrayConverterFactory $reportArrayConverterFactory;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\ReportArrayConverterFactory $reportArrayConverterFactory
     */
    public function __construct(ReportArrayConverterFactory $reportArrayConverterFactory)
    {
        $this->reportArrayConverterFactory = $reportArrayConverterFactory;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return string
     */
    public function serialize(ContextInterface $context): string
    {
        $data = [
            'tags' => $context->getTags(),
            'resolved_values' => $context->getResolvedValues(),
            'messages' => $this->convertMessagesToArray($context->getMessages()),
            'reports' => array_map([$this, 'convertReportToArray'], $context->getReports()),
        ];

        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    /**
     * @param string $content
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function deserialize(string $content): ContextInterface
    {
        $context = new Context();
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        if (array_key_exists('tags', $data) && is_array($data['tags'])) {
            $context->setTags($data['tags']);
        }

        if (array_key_exists('resolved_values', $data) && is_array($data['resolved_values'])) {
            $context->setResolvedValues($data['resolved_values']);
        }

        if (array_key_exists('messages', $data) && is_array($data['messages'])) {
            foreach ($data['messages'] as $id => $messageData) {
                if (!$messageData['message']) {
                    continue;
                }

                $context->addMessage(
                    $id,
                    new Message($messageData['message'], $messageData['verbosity'] ?? MessageInterface::INFO),
                );
            }
        }

        if (array_key_exists('reports', $data) && is_array($data['reports'])) {
            foreach ($data['reports'] as $reportData) {
                $report = $this->getReportFromArray($reportData);

                $context->addReport($report);
            }
        }

        return $context;
    }

    /**
     * @param array<string, \SprykerSdk\SdkContracts\Entity\MessageInterface> $messages
     *
     * @return array<string, array>
     */
    protected function convertMessagesToArray(array $messages): array
    {
        $messagesData = [];
        foreach ($messages as $id => $message) {
            $messagesData[$id] = [
                'message' => $message->getMessage(),
                'verbosity' => $message->getVerbosity(),
            ];
        }

        return $messagesData;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Report\ReportInterface $report
     *
     * @return array
     */
    protected function convertReportToArray(ReportInterface $report): array
    {
        $reportArrayConverter = $this->reportArrayConverterFactory->getArrayConverterByReport($report);

        return $reportArrayConverter->toArray($report);
    }

    /**
     * @param array $reportData
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\InvalidReportTypeException
     *
     * @return \SprykerSdk\SdkContracts\Report\ReportInterface
     */
    protected function getReportFromArray(array $reportData): ReportInterface
    {
        if (!isset($reportData['type'])) {
            throw new InvalidReportTypeException(sprintf('Unable to find report data type in "%s"', json_encode($reportData, JSON_THROW_ON_ERROR)));
        }

        return $this->reportArrayConverterFactory->getArrayConverterByType($reportData['type'])->fromArray($reportData);
    }
}

<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Helper\Core\Application\Service;

use Codeception\Module;
use SprykerSdk\Sdk\Core\Appplication\Dto\Violation\PackageViolationReport;
use SprykerSdk\Sdk\Core\Appplication\Dto\Violation\Violation;
use SprykerSdk\Sdk\Core\Appplication\Dto\Violation\ViolationReport;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Violation\PackageViolationReportInterface;
use SprykerSdk\SdkContracts\Violation\ViolationInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

class ContextSerializerHelper extends Module
{
    /**
     * @param array<string, mixed> $resolvedValues
     * @param array<string> $tags
     * @param array<string, array> $messages
     * @param array<array> $violationReports
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function createContext(
        array $resolvedValues = [],
        array $tags = [],
        array $messages = [],
        array $violationReports = []
    ): ContextInterface {
        $context = new Context();
        $context->setResolvedValues($resolvedValues);
        $context->setTags($tags);

        foreach ($messages as $key => $message) {
            $context->addMessage($key, new Message($message['message'], $message['verbosity']));
        }

        foreach ($violationReports as $violationReport) {
            $context->addViolationReport($this->createViolationReport($violationReport));
        }

        return $context;
    }

    /**
     * @param array $violationReport
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface
     */
    public function createViolationReport(array $violationReport): ViolationReportInterface
    {
        return new ViolationReport(
            $violationReport['project'],
            $violationReport['path'],
            array_map([$this, 'createViolationReportConverter'], $violationReport['violations']),
            array_map([$this, 'createPackageViolationReport'], $violationReport['packages']),
        );
    }

    /**
     * @param array $reportData
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationInterface
     */
    public function createViolationReportConverter(array $reportData): ViolationInterface
    {
        return new Violation(
            $reportData['id'],
            $reportData['message'],
            $reportData['severity'],
            $reportData['priority'],
            $reportData['class'],
            $reportData['start_line'],
            $reportData['end_line'],
            $reportData['start_column'],
            $reportData['end_column'],
            $reportData['method'],
            $reportData['additional_attributes'],
            $reportData['is_fixable'],
            $reportData['produced_by'],
        );
    }

    /**
     * @param array $report
     *
     * @return \SprykerSdk\SdkContracts\Violation\PackageViolationReportInterface
     */
    public function createPackageViolationReport(array $report): PackageViolationReportInterface
    {
        $fileViolations = [];
        foreach ($report['file_violations'] as $key => $fileViolation) {
            $fileViolations[$key] = array_map([$this, 'createViolationReportConverter'], $fileViolation);
        }

        return new PackageViolationReport(
            $report['package'],
            $report['path'],
            array_map([$this, 'createViolationReportConverter'], $report['violations']),
            $fileViolations,
        );
    }

    /**
     * @param array|null $messages
     *
     * @return array
     */
    public function createArrayContext(?array $messages = null): array
    {
        $defaultMessages = [
            'key1' => ['message' => 'Command executed', 'verbosity' => 2],
            'key2' => ['message' => 'Command error', 'verbosity' => 4],
        ];

        return [
            'tags' => ['exampleA'],
            'resolved_values' => [
                'key' => 'resolved value',
            ],
            'messages' => $messages ?? $defaultMessages,
            'violation_reports' => [
                [
                    'path' => '/path/to/file',
                    'project' => 'b2c',
                    'violations' => [
                        [
                            'id' => 'task',
                            'message' => 'violation message',
                            'severity' => 'ERROR',
                            'additional_attributes' => [],
                            'class' => 'Foo',
                            'method' => 'foo',
                            'start_column' => 1,
                            'end_column' => 2,
                            'start_line' => 1,
                            'end_line' => 10,
                            'is_fixable' => true,
                            'priority' => 'priority',
                            'produced_by' => 'task',
                            'fix' => null,
                        ],
                    ],
                    'packages' => [
                        [
                            'violations' => [
                                [
                                    'id' => 'task',
                                    'message' => 'violation message',
                                    'severity' => 'ERROR',
                                    'additional_attributes' => [],
                                    'class' => 'Foo',
                                    'method' => 'foo',
                                    'start_column' => 1,
                                    'end_column' => 2,
                                    'start_line' => 1,
                                    'end_line' => 10,
                                    'is_fixable' => true,
                                    'priority' => 'priority',
                                    'produced_by' => 'task',
                                    'fix' => null,
                                ],
                            ],
                            'path' => '/path/to/another/file',
                            'package' => 'Pyz\\Test\\',
                            'file_violations' => [
                                'key' => [
                                    [
                                        'id' => 'somefile',
                                        'message' => 'violation message',
                                        'severity' => 'ERROR',
                                        'additional_attributes' => [],
                                        'class' => 'Foo',
                                        'method' => 'foo',
                                        'start_column' => 1,
                                        'end_column' => 2,
                                        'start_line' => 1,
                                        'end_line' => 10,
                                        'is_fixable' => true,
                                        'priority' => 'priority',
                                        'produced_by' => 'task',
                                        'fix' => null,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function createArrayViolationReport(): array
    {
        return [
            'path' => '/path/to/file',
            'project' => 'b2c',
            'violations' => [
                [
                    'id' => 'task',
                    'message' => 'violation message',
                    'additional_attributes' => [],
                    'severity' => 'ERROR',
                    'fix' => null,
                    'class' => 'Foo',
                    'method' => 'foo',
                    'start_column' => 1,
                    'end_column' => 2,
                    'start_line' => 1,
                    'end_line' => 10,
                    'is_fixable' => true,
                    'priority' => 'priority',
                    'produced_by' => 'task',
                ],
            ],
            'packages' => [
                [
                    'violations' => [
                        [
                            'id' => 'task',
                            'message' => 'violation message',
                            'severity' => 'ERROR',
                            'fix' => null,
                            'additional_attributes' => [],
                            'class' => 'Foo',
                            'method' => 'foo',
                            'start_column' => 1,
                            'end_column' => 2,
                            'start_line' => 1,
                            'end_line' => 10,
                            'is_fixable' => true,
                            'priority' => 'priority',
                            'produced_by' => 'task',
                        ],
                    ],
                    'path' => '/path/to/another/file',
                    'package' => 'Pyz\\Test\\',
                    'file_violations' => [
                        'key1' => [
                            [
                                'id' => 'somefile',
                                'message' => 'violation message',
                                'severity' => 'ERROR',
                                'fix' => null,
                                'additional_attributes' => [],
                                'class' => 'Foo',
                                'method' => 'foo',
                                'start_column' => 1,
                                'end_column' => 2,
                                'start_line' => 1,
                                'end_line' => 10,
                                'is_fixable' => true,
                                'priority' => 'priority',
                                'produced_by' => 'task',
                            ],
                        ],
                    ],
                ],
                [
                    'violations' => [
                        [
                            'id' => 'task',
                            'message' => 'violation message',
                            'severity' => 'ERROR',
                            'fix' => null,
                            'additional_attributes' => [],
                            'class' => 'Foo',
                            'method' => 'foo',
                            'start_column' => 1,
                            'end_column' => 2,
                            'start_line' => 1,
                            'end_line' => 10,
                            'is_fixable' => true,
                            'priority' => 'priority',
                            'produced_by' => 'task',
                        ],
                    ],
                    'path' => '/path/to/another/file',
                    'package' => 'Pyz\\Test\\',
                    'file_violations' => [
                        'key1' => [
                            [
                                'id' => 'somefile',
                                'message' => 'violation message',
                                'severity' => 'ERROR',
                                'fix' => null,
                                'additional_attributes' => [],
                                'class' => 'Foo',
                                'method' => 'foo',
                                'start_column' => 1,
                                'end_column' => 2,
                                'start_line' => 1,
                                'end_line' => 10,
                                'is_fixable' => true,
                                'priority' => 'priority',
                                'produced_by' => 'task',
                            ],
                        ],
                        'key2' => [
                            [
                                'id' => 'somefile',
                                'message' => 'violation message',
                                'severity' => 'ERROR',
                                'fix' => null,
                                'additional_attributes' => [],
                                'class' => 'Foo',
                                'method' => 'foo',
                                'start_column' => 1,
                                'end_column' => 2,
                                'start_line' => 1,
                                'end_line' => 10,
                                'is_fixable' => true,
                                'priority' => 'priority',
                                'produced_by' => 'task',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}

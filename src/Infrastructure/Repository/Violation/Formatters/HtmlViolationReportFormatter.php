<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository\Violation\Formatters;

use SprykerSdk\Sdk\Core\Appplication\Violation\ViolationReportFormatterInterface;
use SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface;
use SprykerSdk\Sdk\Infrastructure\Repository\Violation\ViolationPathReader;
use SprykerSdk\Sdk\Infrastructure\TemplateEngine\Twig;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

class HtmlViolationReportFormatter implements ViolationReportFormatterInterface
{
    /**
     * @var string
     */
    protected const VIOLATION_TEMPLATE = 'violation_report.html.twig';

    /**
     * @var string
     */
    protected const URI_SCHEME = 'file://';

    /**
     * @var string
     */
    protected const HTML_EXT = '.html';

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface
     */
    protected ViolationReportFileMapperInterface $violationReportFileMapper;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\Violation\ViolationPathReader
     */
    protected ViolationPathReader $violationPathReader;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\TemplateEngine\Twig
     */
    protected Twig $templating;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface $violationReportFileMapper
     * @param \SprykerSdk\Sdk\Infrastructure\Repository\Violation\ViolationPathReader $violationPathReader
     * @param \SprykerSdk\Sdk\Infrastructure\TemplateEngine\Twig $templating
     */
    public function __construct(
        ViolationReportFileMapperInterface $violationReportFileMapper,
        ViolationPathReader $violationPathReader,
        Twig $templating
    ) {
        $this->violationReportFileMapper = $violationReportFileMapper;
        $this->violationPathReader = $violationPathReader;
        $this->templating = $templating;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return 'html';
    }

    /**
     * @param string $name
     * @param \SprykerSdk\SdkContracts\Violation\ViolationReportInterface $violationReport
     *
     * @return void
     */
    public function format(string $name, ViolationReportInterface $violationReport): void
    {
        if (count($violationReport->getViolations()) === 0 && count($violationReport->getPackages()) === 0) {
            return;
        }

        $violations = $this->violationReportFileMapper->mapViolationReportToHtml($violationReport);
        $this->createReports($name, $violations);
    }

    /**
     * @param string $name
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface|null
     */
    public function read(string $name): ?ViolationReportInterface
    {
        return null;
    }

    /**
     * @param string $commandName
     * @param array $violations
     *
     * @return void
     */
    protected function createReports(string $commandName, array $violations): void
    {
        $reportDirPath = rtrim($this->violationPathReader->getViolationReportDirPath(), '/') . DIRECTORY_SEPARATOR;

        $statistics = [
            'project' => count($violations['project']['violations']),
            'packages' => array_reduce($violations['packages'], fn ($initialValue, $value) => count($value['violations']) + $initialValue, 0),
            'files' => array_reduce($violations['files'], fn ($initialValue, $value) => count($value['violations']) + $initialValue, 0),
        ];

        $links = $this->prepareLinks($violations, $reportDirPath, $commandName);

        $this->createFile([], $statistics, $links, $reportDirPath, $this->violationPathReader->getViolationReportPath($commandName, 'html'));

        if ($statistics['project'] > 0) {
            $projectDirPath = $reportDirPath . $commandName . DIRECTORY_SEPARATOR;
            foreach ($violations['project']['violations'] as $violation) {
                $this->createFile($violation, $statistics, $links, $projectDirPath, $projectDirPath . $violation['id'] . static::HTML_EXT);
            }
        }

        if ($statistics['packages'] > 0) {
            $packagesReportDirPath = $reportDirPath . $commandName . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR;

            foreach ($violations['packages'] as $package) {
                foreach ($package['violations'] as $violation) {
                    $this->createFile($violation, $statistics, $links, $packagesReportDirPath, $packagesReportDirPath . $violation['id'] . static::HTML_EXT);
                }
            }
        }

        if ($statistics['files'] > 0) {
            $filesReportDirPath = $reportDirPath . $commandName . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR;

            foreach ($violations['files'] as $file) {
                foreach ($file['violations'] as $violation) {
                    $this->createFile($violation, $statistics, $links, $filesReportDirPath, $filesReportDirPath . $violation['id'] . static::HTML_EXT);
                }
            }
        }
    }

    /**
     * @param array $violations
     * @param array $statistics
     * @param array $links
     * @param string $dirPath
     * @param string $fileName
     *
     * @return void
     */
    private function createFile(array $violations, array $statistics, array $links, string $dirPath, string $fileName): void
    {
        if (!is_dir($dirPath)) {
            mkdir(
                $dirPath,
                0777,
                true,
            );
        }

        $content = $this->templating->render(
            static::VIOLATION_TEMPLATE,
            ['violation' => $violations, 'statistics' => $statistics, 'links' => $links],
        );

        file_put_contents($fileName, $content);
    }

    /**
     * @param array $violations
     * @param string $reportDirPath
     * @param string $commandName
     *
     * @return array
     */
    private function prepareLinks(array $violations, string $reportDirPath, string $commandName): array
    {
        $links = [];

        if (count($violations['packages']) > 0) {
            foreach ($violations['packages'] as $package) {
                if (!count($package['violations']) > 0) {
                    continue;
                }

                foreach ($package['violations'] as $violation) {
                    $links['packages'][] =
                        [
                            'link' => static::URI_SCHEME . $reportDirPath . $commandName
                                . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . $violation['id'] . static::HTML_EXT,
                            'name' => $violation['id'],
                        ];
                }
            }
        }

        if (count($violations['files']) > 0) {
            foreach ($violations['files'] as $file) {
                if (!count($file['violations']) > 0) {
                    continue;
                }

                foreach ($file['violations'] as $violation) {
                    $links['files'][] =
                        [
                            'link' => static::URI_SCHEME . $reportDirPath . $commandName . DIRECTORY_SEPARATOR
                                . 'files' . DIRECTORY_SEPARATOR . $violation['id'] . static::HTML_EXT,
                            'name' => $violation['id'],
                        ];
                }
            }
        }

        if (count($violations['project']['violations']) > 0) {
            foreach ($violations['project']['violations'] as $projectViolation) {
                $links['project'][] =
                    [
                        'link' => static::URI_SCHEME . $reportDirPath . $commandName . DIRECTORY_SEPARATOR
                            . $projectViolation['id'] . static::HTML_EXT,
                        'name' => $projectViolation['id'],
                    ];
            }
        }

        return $links;
    }
}

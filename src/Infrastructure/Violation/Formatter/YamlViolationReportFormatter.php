<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Violation\Formatter;

use SprykerSdk\Sdk\Core\Application\Violation\ViolationReportFormatterInterface;
use SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface;
use SprykerSdk\Sdk\Infrastructure\Violation\ViolationPathReader;
use SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface;
use Symfony\Component\Yaml\Yaml;

class YamlViolationReportFormatter implements ViolationReportFormatterInterface
{
    /**
     * @var string
     */
    public const FORMAT = 'yaml';

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface
     */
    protected ViolationReportFileMapperInterface $violationReportFileMapper;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Violation\ViolationPathReader
     */
    protected ViolationPathReader $violationPathReader;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Violation\Formatter\ViolationReportDecorator
     */
    protected ViolationReportDecorator $violationReportDecorator;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface $violationReportFileMapper
     * @param \SprykerSdk\Sdk\Infrastructure\Violation\ViolationPathReader $violationPathReader
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param \SprykerSdk\Sdk\Infrastructure\Violation\Formatter\ViolationReportDecorator $violationReportDecorator
     */
    public function __construct(
        ViolationReportFileMapperInterface $violationReportFileMapper,
        ViolationPathReader $violationPathReader,
        Yaml $yamlParser,
        ViolationReportDecorator $violationReportDecorator
    ) {
        $this->violationReportFileMapper = $violationReportFileMapper;
        $this->violationPathReader = $violationPathReader;
        $this->yamlParser = $yamlParser;
        $this->violationReportDecorator = $violationReportDecorator;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return static::FORMAT;
    }

    /**
     * @param string $name
     * @param \SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface $violationReport
     *
     * @return void
     */
    public function format(string $name, ViolationReportInterface $violationReport): void
    {
        $violationReport = $this->violationReportDecorator->decorate($violationReport);

        $violationReportStructure = $this->violationReportFileMapper->mapViolationReportToYamlStructure($violationReport);
        $reportDir = $this->violationPathReader->getViolationReportDirPath();
        if (!is_dir($reportDir)) {
            mkdir($reportDir, 0777, true);
        }
        file_put_contents($this->violationPathReader->getViolationReportPath($name), $this->yamlParser::dump($violationReportStructure));
    }

    /**
     * @param string $name
     *
     * @return \SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface|null
     */
    public function read(string $name): ?ViolationReportInterface
    {
        $violationReportData = $this->yamlParser::parseFile($this->violationPathReader->getViolationReportPath($name));

        return $this->violationReportFileMapper->mapFileStructureToViolationReport($violationReportData);
    }
}

<?php

namespace SprykerSdk\Sdk\Infrastructure\Repository\Violation\Formatters;

use SprykerSdk\Sdk\Core\Appplication\Violation\ViolationReportFormatterInterface;
use SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface;
use SprykerSdk\Sdk\Infrastructure\Repository\Violation\ViolationPathReader;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;
use Symfony\Component\Yaml\Yaml;

class YamlViolationReportFormatter implements ViolationReportFormatterInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface
     */
    protected ViolationReportFileMapperInterface $violationReportFileMapper;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\Violation\ViolationPathReader
     */
    protected ViolationPathReader $violationPathReader;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface $violationReportFileMapper
     * @param \SprykerSdk\Sdk\Infrastructure\Repository\Violation\ViolationPathReader $violationPathReader
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     */
    public function __construct(
        ViolationReportFileMapperInterface $violationReportFileMapper,
        ViolationPathReader $violationPathReader,
        Yaml $yamlParser
    ) {
        $this->violationReportFileMapper = $violationReportFileMapper;
        $this->violationPathReader = $violationPathReader;
        $this->yamlParser = $yamlParser;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return 'yaml';
    }

    /**
     * @param string $name
     * @param \SprykerSdk\SdkContracts\Violation\ViolationReportInterface $violationReport
     *
     * @return mixed
     */
    public function format(string $name, ViolationReportInterface $violationReport): void
    {
        $violationReportStructure = $this->violationReportFileMapper->mapViolationReportToYamlStructure($violationReport);

        file_put_contents($this->violationPathReader->getViolationReportPath($name), $this->yamlParser->dump($violationReportStructure));
    }

    /**
     * @param string $name
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface|null
     */
    public function read(string $name): ?ViolationReportInterface
    {
        $violationReportData = $this->yamlParser->parseFile($this->violationPathReader->getViolationReportPath($name));

        return $this->violationReportFileMapper->mapFileStructureToViolationReport($violationReportData);
    }
}

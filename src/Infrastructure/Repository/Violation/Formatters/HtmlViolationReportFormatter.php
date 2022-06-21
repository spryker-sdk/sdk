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
        $violations = $this->violationReportFileMapper->mapViolationReportToHtml($violationReport);
        $template = static::VIOLATION_TEMPLATE;
        $content = $this->templating->render($template, ['report' => $violations]);
        file_put_contents($this->violationPathReader->getViolationReportPath($name, 'html'), $content);
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
}

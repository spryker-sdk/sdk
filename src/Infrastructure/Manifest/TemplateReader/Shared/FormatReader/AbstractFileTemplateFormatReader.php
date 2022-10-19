<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Manifest\TemplateReader\Shared\FormatReader;

use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestRequestDtoInterface;
use Twig\Environment;

abstract class AbstractFileTemplateFormatReader implements TemplateFormatReaderInterface
{
    /**
     * @var \Twig\Environment
     */
    protected Environment $twig;

    /**
     * @var string
     */
    protected string $templatePath;

    /**
     * @param \Twig\Environment $twig
     * @param string $templatePath
     */
    public function __construct(Environment $twig, string $templatePath)
    {
        $this->twig = $twig;
        $this->templatePath = $templatePath;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestRequestDtoInterface $manifestDto
     *
     * @return string
     */
    public function readTemplate(ManifestRequestDtoInterface $manifestDto): string
    {
        return $this->twig->render($this->templatePath, ['data' => $manifestDto]);
    }
}

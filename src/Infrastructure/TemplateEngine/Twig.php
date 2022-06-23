<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\TemplateEngine;

use SprykerSdk\Sdk\Infrastructure\Repository\Violation\ViolationPathReader;
use SprykerSdk\Sdk\Infrastructure\TemplateEngine\Extensions\TypeCastingExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TemplateWrapper;

class Twig
{
    /**
     * @var \Twig\Environment
     */
    protected Environment $templating;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Repository\Violation\ViolationPathReader $violationPathReader
     */
    public function __construct(ViolationPathReader $violationPathReader)
    {
        $loader = new FilesystemLoader(rtrim($violationPathReader->getViolationTemplateDirPath(), '/') . DIRECTORY_SEPARATOR);
        $this->templating = new Environment($loader);
        $this->addExtensions();
    }

    /**
     * @param \Twig\TemplateWrapper|string $name The template name
     * @param array $context An array of parameters to pass to the template
     *
     * @return string
     */
    public function render(TemplateWrapper|string $name, array $context = []): string
    {
        return $this->templating->render($name, $context);
    }

    /**
     * @return void
     */
    protected function addExtensions(): void
    {
        $this->templating->addExtension(new TypeCastingExtension());
    }
}

<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Twig\Extension;

use ReflectionClass;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ClassConstantFetcher extends AbstractExtension
{
    /**
     * @return array<\Twig\TwigFunction>
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('class_const', [$this, 'getClassConstant']),
        ];
    }

    /**
     * @param class-string $className
     * @param mixed $constantValue
     *
     * @return string|null
     */
    public function getClassConstant(string $className, $constantValue): ?string
    {
        $reflection = new ReflectionClass($className);

        foreach ($reflection->getConstants() as $name => $value) {
            if ($value === $constantValue) {
                return sprintf('%s::%s', $className, $name);
            }
        }

        return null;
    }
}

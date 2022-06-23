<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\TemplateEngine\Extensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TypeCastingExtension extends AbstractExtension
{
    /**
     * @return array<int, \Twig\TwigFilter>
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('int', function ($value) {
                return (int)$value;
            }),
            new TwigFilter('float', function ($value) {
                return (float)$value;
            }),
            new TwigFilter('string', function ($value) {
                return match ($value) {
                    null => 'null',
                    false => 'false',
                    true => 'true',
                    default => (string)$value,
                };
            }),
            new TwigFilter('bool', function ($value) {
                return (bool)$value;
            }),
            new TwigFilter('array', function (object $value) {
                return (array)$value;
            }),
            new TwigFilter('object', function (array $value) {
                return (object)$value;
            }),
        ];
    }
}

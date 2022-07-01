<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\DependencyInjection\EnvVarProcessor;

use Closure;
use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;

class UnameEnvVarProcessor implements EnvVarProcessorInterface
{
    /**
     * @param string $prefix
     * @param string $name
     * @param \Closure $getEnv
     *
     * @return string
     */
    public function getEnv(string $prefix, string $name, Closure $getEnv): string
    {
        $env = $getEnv($name);

        return $env ?: php_uname();
    }

    /**
     * @return array<string, string>
     */
    public static function getProvidedTypes(): array
    {
        return [
            'uname' => 'string',
        ];
    }
}

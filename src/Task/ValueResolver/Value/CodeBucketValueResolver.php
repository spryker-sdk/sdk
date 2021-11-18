<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\ValueResolver\Value;

class CodeBucketValueResolver implements ValueResolverInterface
{
    /**
     * @return string|null
     */
    public function getParameterName(): ?string
    {
        return 'code_bucket';
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'CODE_BUCKET';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Base code bucket';
    }

    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function getValue(array $settings)
    {
        return null;
    }

    /**
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return [];
    }

    /**
     * E.g.: string, bool, int, path
     *
     * @return string
     */
    public function getType(): string
    {
        return 'bool';
    }
}

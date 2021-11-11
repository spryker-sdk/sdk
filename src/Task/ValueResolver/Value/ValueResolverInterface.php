<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\ValueResolver\Value;

interface ValueResolverInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @param array $parameters
     *
     * @return mixed
     */
    public function getValue(array $parameters);

    /**
     * @return array<string>
     */
    public function getSettingPaths(): array;

    /**
     * E.g.: string, bool, int, path
     *
     * @return string
     */
    public function getType(): string;
}

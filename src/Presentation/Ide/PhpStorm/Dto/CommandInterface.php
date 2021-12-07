<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto;

interface CommandInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string|null
     */
    public function getHelp(): ?string;

    /**
     * @return array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\ParamInterface>
     */
    public function getParams(): array;

    /**
     * @return array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\OptionInterface>
     */
    public function getOptionsBefore(): array;
}

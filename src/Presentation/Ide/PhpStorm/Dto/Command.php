<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto;

class Command implements CommandInterface
{
    protected string $name;

    /**
     * @var array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\ParamInterface>
     */
    protected array $params;

    /**
     * @var array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\OptionInterface>
     */
    protected array $optionsBefore = [];

    protected ?string $help = null;

    /**
     * @param string $name
     * @param array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\ParamInterface> $params
     * @param array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\OptionInterface> $optionsBefore
     * @param string|null $help
     */
    public function __construct(string $name, array $params, array $optionsBefore = [], ?string $help = null)
    {
        $this->name = $name;
        $this->params = $params;
        $this->optionsBefore = $optionsBefore;
        $this->help = $help;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\ParamInterface>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return array
     */
    public function getOptionsBefore(): array
    {
        return $this->optionsBefore;
    }

    /**
     * @return string|null
     */
    public function getHelp(): ?string
    {
        return $this->help;
    }
}

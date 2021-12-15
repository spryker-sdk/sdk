<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto;

class Option implements OptionInterface
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string|null
     */
    protected ?string $shortcut;

    /**
     * @var string|null
     */
    protected ?string $help = null;

    /**
     * @param string $name
     * @param string|null $shortcut
     * @param string|null $help
     */
    public function __construct(string $name, ?string $shortcut, ?string $help = null)
    {
        $this->name = $name;
        $this->shortcut = $shortcut;
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
     * @return string|null
     */
    public function getShortcut(): ?string
    {
        return $this->shortcut;
    }

    /**
     * @return string|null
     */
    public function getHelp(): ?string
    {
        return $this->help;
    }
}

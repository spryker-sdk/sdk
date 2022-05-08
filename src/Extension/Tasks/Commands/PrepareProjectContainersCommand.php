<?php

namespace SprykerSdk\Sdk\Extension\Tasks\Commands;

use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;

class PrepareProjectContainersCommand implements CommandInterface
{
    /**
     * @return string
     */
    public function getCommand(): string
    {
        return 'cd %pbc_name% && git clone https://github.com/spryker/docker-sdk.git ./docker && ./docker/sdk boot -s deploy.dev.yml';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'local_cli';
    }

    /**
     * @return array<string>
     */
    public function getTags(): array
    {
        return [];
    }

    /**
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return true;
    }

    /**
     * @return ConverterInterface|null
     */
    public function getViolationConverter(): ?ConverterInterface
    {
        return null;
    }
}

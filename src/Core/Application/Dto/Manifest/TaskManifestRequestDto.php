<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\Manifest;

class TaskManifestRequestDto implements ManifestRequestDtoInterface
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var string
     */
    protected string $shortDescription;

    /**
     * @var string
     */
    protected string $version;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var string
     */
    protected string $command;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile
     */
    protected ManifestFile $manifestFile;

    /**
     * @param string $id
     * @param string $shortDescription
     * @param string $version
     * @param string $type
     * @param string $command
     * @param \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile $manifestFile
     */
    public function __construct(
        string $id,
        string $shortDescription,
        string $version,
        string $type,
        string $command,
        ManifestFile $manifestFile
    ) {
        $this->id = $id;
        $this->shortDescription = $shortDescription;
        $this->version = $version;
        $this->type = $type;
        $this->command = $command;
        $this->manifestFile = $manifestFile;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile
     */
    public function getManifestFile(): ManifestFile
    {
        return $this->manifestFile;
    }
}

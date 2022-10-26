<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\Manifest;

class TaskManifestResponseDto implements ManifestResponseDtoInterface
{
    /**
     * @var string
     */
    protected string $createdFileName;

    /**
     * @param string $createdFileName
     */
    public function __construct(string $createdFileName)
    {
        $this->createdFileName = $createdFileName;
    }

    /**
     * @return string
     */
    public function getCreatedFileName(): string
    {
        return $this->createdFileName;
    }
}

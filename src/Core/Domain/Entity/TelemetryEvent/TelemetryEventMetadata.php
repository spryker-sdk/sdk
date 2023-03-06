<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent;

class TelemetryEventMetadata implements TelemetryEventMetadataInterface
{
    /**
     * @var string|null
     */
    protected ?string $developerEmail;

    /**
     * @var string|null
     */
    protected ?string $projectName;

    /**
     * @var string|null
     */
    protected ?string $executionEnv;

    /**
     * @param string|null $developerEmail
     * @param string|null $projectName
     * @param string|null $executionEnv
     */
    public function __construct(?string $developerEmail, ?string $projectName, ?string $executionEnv)
    {
        $this->developerEmail = $developerEmail;
        $this->projectName = $projectName;
        $this->executionEnv = $executionEnv;
    }

    /**
     * @return string|null
     */
    public function getDeveloperEmail(): ?string
    {
        return $this->developerEmail;
    }

    /**
     * @return string|null
     */
    public function getProjectName(): ?string
    {
        return $this->projectName;
    }

    /**
     * @return string|null
     */
    public function getExecutionEnv(): ?string
    {
        return $this->executionEnv;
    }
}

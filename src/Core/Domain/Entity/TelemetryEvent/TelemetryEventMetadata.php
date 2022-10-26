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
    protected ?string $developerGithubAccount;

    /**
     * @var string|null
     */
    protected ?string $projectName;

    /**
     * @param string|null $developerEmail
     * @param string|null $developerGithubAccount
     * @param string|null $projectName
     */
    public function __construct(?string $developerEmail, ?string $developerGithubAccount, ?string $projectName)
    {
        $this->developerEmail = $developerEmail;
        $this->developerGithubAccount = $developerGithubAccount;
        $this->projectName = $projectName;
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
    public function getDeveloperGithubAccount(): ?string
    {
        return $this->developerGithubAccount;
    }

    /**
     * @return string|null
     */
    public function getProjectName(): ?string
    {
        return $this->projectName;
    }
}

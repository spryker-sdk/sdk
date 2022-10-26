<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent;

interface TelemetryEventMetadataInterface
{
    /**
     * @return string|null
     */
    public function getDeveloperEmail(): ?string;

    /**
     * @return string|null
     */
    public function getDeveloperGithubAccount(): ?string;

    /**
     * @return string|null
     */
    public function getProjectName(): ?string;
}

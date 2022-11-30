<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Processor;

use SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto;
use SprykerSdk\Sdk\Core\Application\Initializer\ProjectSettingsInitializerInterface;
use SprykerSdk\Sdk\Presentation\RestApi\OpenApi\OpenApiRequest;

class SdkInitProjectProcessor
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Initializer\ProjectSettingsInitializerInterface
     */
    protected ProjectSettingsInitializerInterface $projectSettingsInitializer;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Initializer\ProjectSettingsInitializerInterface $projectSettingsInitializer
     */
    public function __construct(ProjectSettingsInitializerInterface $projectSettingsInitializer)
    {
        $this->projectSettingsInitializer = $projectSettingsInitializer;
    }

    /**
     * @param \SprykerSdk\Sdk\Presentation\RestApi\OpenApi\OpenApiRequest $request
     *
     * @return void
     */
    public function process(OpenApiRequest $request): void
    {
        $projectSettingsInitDto = new ProjectSettingsInitDto(
            $request->getAttributes(),
            $request->getAttribute('default', false),
        );

        $this->projectSettingsInitializer->initialize($projectSettingsInitDto);
    }
}

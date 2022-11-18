<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Processor;

use SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto;
use SprykerSdk\Sdk\Core\Application\Initializer\ProjectSettingsInitializerInterface;
use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiField;
use Symfony\Component\HttpFoundation\Request;

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function process(Request $request)
    {
        /** @var array<string, mixed> $data */
        $data = $request->request->get(OpenApiField::DATA);
        $projectSettingsInitDto = new ProjectSettingsInitDto(
            $data[OpenApiField::ATTRIBUTES],
            $request->request->getBoolean('default'),
        );

        $this->projectSettingsInitializer->initialize($projectSettingsInitDto);
    }
}

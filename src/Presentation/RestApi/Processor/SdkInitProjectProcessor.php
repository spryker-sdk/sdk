<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Processor;

use SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto;
use SprykerSdk\Sdk\Core\Application\Initializer\ProjectSettingsInitializerInterface;
use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiField;
use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiType;
use SprykerSdk\Sdk\Presentation\RestApi\Factory\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SdkInitProjectProcessor
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Initializer\ProjectSettingsInitializerInterface
     */
    protected ProjectSettingsInitializerInterface $projectSettingsInitializer;

    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\Factory\ResponseFactory
     */
    protected ResponseFactory $responseFactory;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Initializer\ProjectSettingsInitializerInterface $projectSettingsInitializer
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Factory\ResponseFactory $responseFactory
     */
    public function __construct(ProjectSettingsInitializerInterface $projectSettingsInitializer, ResponseFactory $responseFactory)
    {
        $this->projectSettingsInitializer = $projectSettingsInitializer;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function process(Request $request): JsonResponse
    {
        /** @var array<string, mixed> $data */
        $data = $request->request->get(OpenApiField::DATA);
        $projectSettingsInitDto = new ProjectSettingsInitDto(
            $data[OpenApiField::ATTRIBUTES],
            $request->request->getBoolean('default'),
        );

        $this->projectSettingsInitializer->initialize($projectSettingsInitDto);

        return $this->responseFactory->createSuccessResponse(
            OpenApiType::SDK_INIT_PROJECT,
            OpenApiType::SDK_INIT_PROJECT,
        );
    }
}

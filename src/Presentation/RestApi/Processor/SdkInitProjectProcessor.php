<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Processor;

use SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto;
use SprykerSdk\Sdk\Core\Application\Initializer\ProjectSettingsInitializerInterface;
use SprykerSdk\Sdk\Presentation\RestApi\Builder\ResponseBuilder;
use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiField;
use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SdkInitProjectProcessor
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Initializer\ProjectSettingsInitializerInterface
     */
    protected ProjectSettingsInitializerInterface $projectSettingsInitializer;

    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\Builder\ResponseBuilder
     */
    protected ResponseBuilder $responseBuilder;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Initializer\ProjectSettingsInitializerInterface $projectSettingsInitializer
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Builder\ResponseBuilder $responseBuilder
     */
    public function __construct(ProjectSettingsInitializerInterface $projectSettingsInitializer, ResponseBuilder $responseBuilder)
    {
        $this->projectSettingsInitializer = $projectSettingsInitializer;
        $this->responseBuilder = $responseBuilder;
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

        return $this->responseBuilder->createSuccessResponse(
            OpenApiType::SDK_INIT_PROJECT,
            OpenApiType::SDK_INIT_PROJECT,
        );
    }
}

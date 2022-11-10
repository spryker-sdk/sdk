<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Processor;

use SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto;
use SprykerSdk\Sdk\Core\Application\Initializer\ProjectSettingsInitializerInterface;
use SprykerSdk\Sdk\Presentation\RestApi\Builder\ResponseBuilder;
use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SdkInitProjectProcessor
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Initializer\ProjectSettingsInitializerInterface
     */
    protected ProjectSettingsInitializerInterface $initializerService;

    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\Builder\ResponseBuilder
     */
    protected ResponseBuilder $responseBuilder;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Initializer\ProjectSettingsInitializerInterface $initializerService
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Builder\ResponseBuilder $responseBuilder
     */
    public function __construct(ProjectSettingsInitializerInterface $initializerService, ResponseBuilder $responseBuilder)
    {
        $this->projectSettingsInitializer = $initializerService;
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function process(Request $request): Response
    {
        $projectSettingsInitDto = new ProjectSettingsInitDto(
            $request->request->all(),
            $request->request->get('default', false),
        );

        $this->projectSettingsInitializer->initialize($projectSettingsInitDto);

        return $this->responseBuilder->buildResponse(
            OpenApiType::SDK_INIT_PROJECT,
            OpenApiType::SDK_INIT_PROJECT,
        );
    }
}

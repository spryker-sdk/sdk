<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Infrastructure\Service\Initializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SdkInitSdkController
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Initializer
     */
    protected Initializer $initializerService;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Initializer $initializerService
     */
    public function __construct(Initializer $initializerService)
    {
        $this->initializerService = $initializerService;
    }

    /**
     * @Nelmio\Areas({"default"})
     *
     * @OA\Tag(name="sdk init")
     *
     * @OA\RequestBody(
     *
     * @OA\JsonContent(
     *          type="object",
     *          required={"developer_email", "developer_github_account"},
     *
     * @OA\Property(
     *              property="developer_email",
     *              type="string",
     *              description="What is your email?",
     *              example="developer@example.com",
     *          ),
     * @OA\Property(
     *              property="developer_github_account",
     *              type="string",
     *              description="What is your github account?",
     *              example="https://github.com/some_test_user",
     *          ),
     *     )
     * )
     *
     * @OA\Response(response=200, description="OK")
     *
     * @OA\Response(response=400, description="Bad Request")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request): Response
    {
        $this->initializerService->initialize($request->request->all());

        return new JsonResponse([]);
    }
}

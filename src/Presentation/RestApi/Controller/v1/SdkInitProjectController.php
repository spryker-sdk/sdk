<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SdkInitProjectController
{
    /**
     * @OA\Tag(name="sdk project init")
     *
     * @OA\RequestBody(
     *
     * @OA\JsonContent(
     *          type="object",
     *
     *          @OA\Property(
     *              property="report_usage_statistics",
     *              type="boolean",
     *              description="Do you agree to send anonymous usage reports to help improving the SDK?",
     *              example=false,
     *          ),
     *          @OA\Property(
     *              property="default_violation_output_format",
     *              type="string",
     *              description="Default qa output report format for the report",
     *              example="output",
     *          ),
     *          @OA\Property(
     *              property="workflow",
     *              type="array",
     *              description="What is the project workflow? (multiple values allowed)",
     *
     *              @OA\Items(
     *                  type="string",
     *                  example="app",
     *              ),
     *          ),
     *
     *          @OA\Property(
     *              property="qa_tasks",
     *              type="array",
     *              description="List of task for QA automation",
     *
     *              @OA\Items(
     *                  type="string",
     *                  example={"validation:php:benchmark", "validation:php:static"}
     *              ),
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
        return new JsonResponse($request->request->all());
    }
}

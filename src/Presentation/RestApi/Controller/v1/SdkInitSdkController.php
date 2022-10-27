<?php

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;

class SdkInitSdkController
{
    /**
     * @OA\Tag(name="sdk init")
     *
     * @OA\Parameter(
     *     name="developer_email",
     *     in="query",
     *     description="What is your email?",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="developer_github_account",
     *     in="query",
     *     description="What is your github account?",
     *     @OA\Schema(type="string")
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        return new JsonResponse(['result' => 'success']);
    }
}

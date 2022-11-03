<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Presentation\RestApi\Executor\Task\RestApiTaskExecutor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HelloController
{
    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\Executor\Task\RestApiTaskExecutor
     */
    protected RestApiTaskExecutor $apiTaskExecutor;

    /**
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Executor\Task\RestApiTaskExecutor $apiTaskExecutor
     */
    public function __construct(RestApiTaskExecutor $apiTaskExecutor)
    {
        $this->apiTaskExecutor = $apiTaskExecutor;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(): Response
    {
        $content = $this->apiTaskExecutor->execute([
            'command' => 'hello:world',
            '--world' => 'World',
            '--somebody' => 'World',
        ]);

        return new JsonResponse(['result' => $content]);
    }
}

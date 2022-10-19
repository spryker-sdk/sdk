<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Presentation\RestApi\Service\RestApiTaskExecutor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HelloController extends AbstractController
{
    /**
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Service\RestApiTaskExecutor $apiTaskExecutor
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function helloWorld(RestApiTaskExecutor $apiTaskExecutor): Response
    {
        $content = $apiTaskExecutor->execute([
            'command' => 'hello:world',
            '--world' => 'World',
            '--somebody' => 'World',
        ]);

        return $this->json(['result' => $content]);
    }
}

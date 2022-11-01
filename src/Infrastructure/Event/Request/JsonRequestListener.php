<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event\Request;

use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class JsonRequestListener
{
    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @return void
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!$this->isApplicable($request)) {
            return;
        }

        if ($request->getContentType() !== 'json') {
            $event->setResponse(new JsonResponse('Invalid content type', Response::HTTP_BAD_REQUEST));

            return;
        }

        try {
            $requestContent = $request->toArray();
        } catch (JsonException $e) {
            $event->setResponse(new JsonResponse('Invalid json string', Response::HTTP_BAD_REQUEST));

            return;
        }

        if (is_array($requestContent)) {
            $request->request->replace($requestContent);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function isApplicable(Request $request): bool
    {
        return is_string($request->getContent())
            && $request->getContent() !== ''
            && strpos($request->getPathInfo(), '/api/doc') !== 0
            && strpos($request->getPathInfo(), '/api/') === 0;
    }
}

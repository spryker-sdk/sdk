<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event\Request;

use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class JsonRequestListener
{
    /**
     * @var array<string>
     */
    protected array $contentTypes;

    /**
     * @param array<string> $contentTypes
     */
    public function __construct(array $contentTypes)
    {
        $this->contentTypes = $contentTypes;
    }

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

        if (!$this->isApplicable($request) || !$this->supports($request)) {
            return;
        }

        try {
            $data = json_decode((string)$request->getContent(), true, 512, \JSON_THROW_ON_ERROR);
            $request->request->replace($data);
        } catch (JsonException $exception) {
            $event->setResponse(new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST));
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function supports(Request $request): bool
    {
        return in_array($request->getContentType(), $this->contentTypes, true) && $request->getContent();
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

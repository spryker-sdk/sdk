<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\ApiDoc;

use OpenApi\Annotations\OpenApi;
use RuntimeException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

class SdkCommandsDescriber extends BaseDescriber
{
    /**
     * @var array
     */
    protected const OPERATION_TAGS = ['SDK commands'];

    /**
     * @var string
     */
    protected const HTTP_METHOD = 'POST';

    /**
     * @var iterable<\SprykerSdk\Sdk\Presentation\RestApi\Controller\CommandControllerInterface>
     */
    protected iterable $controllers;

    /**
     * @var iterable<\Symfony\Component\Console\Command\Command>
     */
    protected iterable $commands;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected RequestStack $requestStack;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected RouterInterface $router;

    /**
     * @param iterable<\SprykerSdk\Sdk\Presentation\RestApi\Controller\CommandControllerInterface> $controllers
     * @param iterable<\Symfony\Component\Console\Command\Command> $commands
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(
        iterable $controllers,
        iterable $commands,
        RequestStack $requestStack,
        RouterInterface $router
    ) {
        $this->controllers = $controllers;
        $this->commands = $commands;
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    /**
     * @param \OpenApi\Annotations\OpenApi $api
     *
     * @return void
     */
    public function describe(OpenApi $api): void
    {
        $routes = $this->router->getRouteCollection();
        $commands = $this->getIndexedCommands();

        /** @var \Symfony\Component\Routing\Route $route */
        foreach ($routes as $route) {
            $this->processRoute($route, $api, $commands);
        }
    }

    /**
     * @return array<string, \Symfony\Component\Console\Command\Command>
     */
    protected function getIndexedCommands(): array
    {
        $indexedCommands = [];

        foreach ($this->commands as $command) {
            $indexedCommands[$command->getName()] = $command;
        }

        return $indexedCommands;
    }

    /**
     * @param \Symfony\Component\Routing\Route $route
     * @param \OpenApi\Annotations\OpenApi $api
     * @param array<string, \Symfony\Component\Console\Command\Command> $commands
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function processRoute(Route $route, OpenApi $api, array $commands): void
    {
        $controllerClass = $route->getDefault('_controller');

        if ($controllerClass === null) {
            return;
        }

        foreach ($this->controllers as $controller) {
            if (!($controller instanceof $controllerClass)) {
                continue;
            }

            $commandName = $controller->getCommandName();

            if (!isset($commands[$commandName])) {
                throw new RuntimeException(sprintf('Commands `%s` not fount', $commandName));
            }

            $this->buildRoute(
                $api,
                $commands[$commandName],
                $route->getPath(),
                static::HTTP_METHOD,
                static::OPERATION_TAGS,
            );
        }
    }
}

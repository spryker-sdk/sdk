<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\ApiDoc;

use Nelmio\ApiDocBundle\Describer\DescriberInterface;
use Nelmio\ApiDocBundle\OpenApiPhp\Util;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\OpenApi;
use OpenApi\Annotations\Operation;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

abstract class BaseDescriber implements DescriberInterface
{
    /**
     * @var string
     */
    protected const JSON_TYPE = 'application/json';

    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\ApiDoc\OpenApiDescriberHelper
     */
    protected OpenApiDescriberHelper $describerHelper;

    /**
     * @param \SprykerSdk\Sdk\Presentation\RestApi\ApiDoc\OpenApiDescriberHelper $describerHelper
     */
    public function __construct(OpenApiDescriberHelper $describerHelper)
    {
        $this->describerHelper = $describerHelper;
    }

    /**
     * @param \OpenApi\Annotations\OpenApi $api
     * @param \Symfony\Component\Console\Command\Command $command
     * @param string $route
     * @param string $httpMethod
     * @param array $operationTags
     *
     * @return void
     */
    protected function buildRoute(
        OpenApi $api,
        Command $command,
        string $route,
        string $httpMethod,
        array $operationTags
    ): void {
        $path = Util::getPath($api, $route);

        $operation = Util::getOperation($path, $httpMethod);
        $operation->tags = $operationTags;

        $this->buildRequestBody($operation, $command);

        $operation->responses = [
            $this->buildSuccessfulResponse($operation),
            $this->buildBadRequestResponse($operation),
        ];
    }

    /**
     * @param \OpenApi\Annotations\Operation $operation
     * @param \Symfony\Component\Console\Command\Command $command
     *
     * @return void
     */
    protected function buildRequestBody(Operation $operation, Command $command): void
    {
        /** @var \OpenApi\Annotations\RequestBody $requestBody */
        $requestBody = Util::getChild($operation, RequestBody::class);
        $requestBody->content = [
            static::JSON_TYPE => new MediaType(['mediaType' => static::JSON_TYPE]),
        ];

        /** @var \OpenApi\Annotations\Schema $schema */
        $schema = Util::getChild($requestBody->content[static::JSON_TYPE], Schema::class);

        $dataProperty = $this->describerHelper->getDataProperty($schema);
        $attributesProperty = $this->describerHelper->getAttributesProperty($dataProperty);
        $this->describerHelper->addIdProperty($dataProperty);
        $this->describerHelper->addTypeProperty($dataProperty);

        $requiredArgumentProperties = $this->createPropertiesFromArguments($command, $attributesProperty);
        $this->createPropertiesFromOptions($command, $attributesProperty);

        $attributesProperty->required = $requiredArgumentProperties;
    }

    /**
     * @param \OpenApi\Annotations\Operation $operation
     *
     * @return \OpenApi\Annotations\Response
     */
    protected function buildSuccessfulResponse(Operation $operation): Response
    {
        $content = [
            static::JSON_TYPE => new MediaType(['mediaType' => static::JSON_TYPE]),
        ];
        $response = $this->describerHelper->createResponse(
            $operation,
            SymfonyResponse::HTTP_OK,
            'OK',
            $content,
        );

        /** @var \OpenApi\Annotations\Schema $responseSchema */
        $responseSchema = Util::getChild($content[static::JSON_TYPE], Schema::class);

        $responseDataProperty = $this->describerHelper->getDataProperty($responseSchema);
        $this->describerHelper->getAttributesProperty($responseDataProperty);
        $this->describerHelper->addIdProperty($responseDataProperty);
        $this->describerHelper->addTypeProperty($responseDataProperty);

        return $response;
    }

    /**
     * @param \OpenApi\Annotations\Operation $operation
     *
     * @return \OpenApi\Annotations\Response
     */
    protected function buildBadRequestResponse(Operation $operation): Response
    {
        $content = [
            static::JSON_TYPE => new MediaType(['mediaType' => static::JSON_TYPE]),
        ];

        $errorResponse = $this->describerHelper->createResponse(
            $operation,
            SymfonyResponse::HTTP_BAD_REQUEST,
            'Bad request',
            $content,
        );

        /** @var \OpenApi\Annotations\Schema $responseSchema */
        $responseSchema = Util::getChild($content[static::JSON_TYPE], Schema::class);

        $this->describerHelper->addCodeProperty($responseSchema);
        $this->describerHelper->addStatusProperty($responseSchema);
        $this->describerHelper->addDetailsProperty($responseSchema);

        return $errorResponse;
    }

    /**
     * @param \Symfony\Component\Console\Command\Command $command
     * @param \OpenApi\Annotations\Property $attributesProperty
     *
     * @return array<string>
     */
    protected function createPropertiesFromArguments(Command $command, Property $attributesProperty): array
    {
        $commandArguments = $command->getDefinition()->getArguments();

        $requiredAttributes = [];
        foreach ($commandArguments as $argument) {
            $this->createProperty(
                $attributesProperty,
                $argument->getName(),
                $argument->isArray(),
                $argument->getDefault(),
                $argument->getDescription(),
            );

            if ($argument->isRequired()) {
                $requiredAttributes[] = $argument->getName();
            }
        }

        return $requiredAttributes;
    }

    /**
     * @param \Symfony\Component\Console\Command\Command $command
     * @param \OpenApi\Annotations\Property $attributesProperty
     *
     * @return void
     */
    protected function createPropertiesFromOptions(Command $command, Property $attributesProperty): void
    {
        $commandOptions = $command->getDefinition()->getOptions();

        foreach ($commandOptions as $option) {
            $this->createProperty(
                $attributesProperty,
                $option->getName(),
                $option->isArray(),
                $option->getDefault(),
                $option->getDescription(),
            );
        }
    }

    /**
     * @param \OpenApi\Annotations\Property $attributesProperty
     * @param string $name
     * @param bool $isArray
     * @param array|string|float|int|bool|null $default
     * @param string $description
     *
     * @return void
     */
    protected function createProperty(
        Property $attributesProperty,
        string $name,
        bool $isArray,
        $default,
        string $description
    ): void {
        $property = Util::getProperty($attributesProperty, $name);
        $property->type = 'string';

        if ($isArray) {
            $property->type = 'array';
            /** @var \OpenApi\Annotations\Items $items */
            $items = Util::getChild($property, Items::class);
            $items->type = 'string';
            $property->items = $items;
        }

        $property->default = $default;
        $property->description = $description;
    }
}

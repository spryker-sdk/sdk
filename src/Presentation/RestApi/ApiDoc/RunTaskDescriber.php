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
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Schema;
use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiField;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;

class RunTaskDescriber implements DescriberInterface
{
    /**
     * @var string
     */
    protected const JSON_TYPE = 'application/json';

    /**
     * @var string
     */
    protected const RUN_TASK_ROUTE = '/api/v1/run-task/%s';

    /**
     * @var array
     */
    protected const OPERATION_TAGS = ['Tasks'];

    /**
     * @var string
     */
    protected const HTTP_METHOD = 'POST';

    /**
     * @var \Symfony\Component\Console\CommandLoader\CommandLoaderInterface
     */
    protected CommandLoaderInterface $commandLoader;

    /**
     * @param \Symfony\Component\Console\CommandLoader\CommandLoaderInterface $commandLoader
     */
    public function __construct(CommandLoaderInterface $commandLoader)
    {
        $this->commandLoader = $commandLoader;
    }

    /**
     * @param \OpenApi\Annotations\OpenApi $api
     *
     * @return void
     */
    public function describe(OpenApi $api): void
    {
        $commandNames = $this->commandLoader->getNames();
        sort($commandNames);

        foreach ($commandNames as $commandName) {
            $command = $this->commandLoader->get($commandName);

            $path = Util::getPath($api, sprintf(static::RUN_TASK_ROUTE, $command->getName()));
            $operation = Util::getOperation($path, static::HTTP_METHOD);
            $operation->tags = static::OPERATION_TAGS;

            /** @var \OpenApi\Annotations\RequestBody $requestBody */
            $requestBody = Util::getChild($operation, RequestBody::class);
            $requestBody->content = [
                static::JSON_TYPE => new MediaType(['mediaType' => static::JSON_TYPE]),
            ];

            /** @var \OpenApi\Annotations\Schema $schema */
            $schema = Util::getChild($requestBody->content[static::JSON_TYPE], Schema::class);

            /** @var \OpenApi\Annotations\Property $dataProperty */
            $dataProperty = Util::getProperty($schema, OpenApiField::DATA);

            $attributesProperty = $this->getAttributesProperty($dataProperty);
            $this->getIdProperty($dataProperty);
            $this->getTypeProperty($dataProperty);

            $requiredArgumentProperties = $this->createPropertiesFromArguments($command, $attributesProperty);
            $requiredOptionProperties = $this->createPropertiesFromOptions($command, $attributesProperty);

            $attributesProperty->required = array_merge(
                $requiredArgumentProperties,
                $requiredOptionProperties,
            );
        }
    }

    /**
     * @param \OpenApi\Annotations\Property $dataProperty
     *
     * @return \OpenApi\Annotations\Property
     */
    protected function getAttributesProperty(Property $dataProperty): Property
    {
        /** @var \OpenApi\Annotations\Property $attributesProperty */
        $attributesProperty = Util::getProperty($dataProperty, OpenApiField::ATTRIBUTES);
        $attributesProperty->type = 'object';

        return $attributesProperty;
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
     * @return array<string>
     */
    protected function createPropertiesFromOptions(Command $command, Property $attributesProperty): array
    {
        $commandOptions = $command->getDefinition()->getOptions();

        $requiredAttributes = [];
        foreach ($commandOptions as $option) {
            $this->createProperty(
                $attributesProperty,
                $option->getName(),
                $option->isArray(),
                $option->getDefault(),
                $option->getDescription(),
            );

            if ($option->isValueRequired()) {
                $requiredAttributes[] = $option->getName();
            }
        }

        return $requiredAttributes;
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

    /**
     * @param \OpenApi\Annotations\Property $dataProperty
     *
     * @return \OpenApi\Annotations\Property
     */
    protected function getIdProperty(Property $dataProperty): Property
    {
        $idProperty = Util::getProperty($dataProperty, OpenApiField::ID);
        $idProperty->type = 'string';

        return $idProperty;
    }

    /**
     * @param \OpenApi\Annotations\Property $dataProperty
     *
     * @return \OpenApi\Annotations\Property
     */
    protected function getTypeProperty(Property $dataProperty): Property
    {
        $typeProperty = Util::getProperty($dataProperty, OpenApiField::TYPE);
        $typeProperty->type = 'string';

        return $typeProperty;
    }
}

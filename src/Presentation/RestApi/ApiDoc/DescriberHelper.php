<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\ApiDoc;

use Nelmio\ApiDocBundle\OpenApiPhp\Util;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\Operation;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;
use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiField;

class DescriberHelper
{
    /**
     * @param \OpenApi\Annotations\Schema $schema
     *
     * @return \OpenApi\Annotations\Property
     */
    public static function getDataProperty(Schema $schema): Property
    {
        $property = Util::getProperty($schema, OpenApiField::DATA);
        $property->type = 'object';

        return $property;
    }

    /**
     * @param \OpenApi\Annotations\Property $dataProperty
     *
     * @return \OpenApi\Annotations\Property
     */
    public static function getAttributesProperty(Property $dataProperty): Property
    {
        $property = Util::getProperty($dataProperty, OpenApiField::ATTRIBUTES);
        $property->type = 'object';

        return $property;
    }

    /**
     * @param \OpenApi\Annotations\Property $dataProperty
     *
     * @return void
     */
    public static function addIdProperty(Property $dataProperty): void
    {
        $property = Util::getProperty($dataProperty, OpenApiField::ID);
        $property->type = 'string';
    }

    /**
     * @param \OpenApi\Annotations\Property $dataProperty
     *
     * @return void
     */
    public static function addTypeProperty(Property $dataProperty): void
    {
        $property = Util::getProperty($dataProperty, OpenApiField::TYPE);
        $property->type = 'string';
    }

    /**
     * @param \OpenApi\Annotations\Schema $responseSchema
     *
     * @return void
     */
    public static function addDetailsProperty(Schema $responseSchema): void
    {
        $property = Util::getProperty($responseSchema, OpenApiField::DETAILS);
        $property->type = 'array';

        /** @var \OpenApi\Annotations\Items $items */
        $items = Util::getChild($property, Items::class);
        $items->type = 'string';
        $property->items = $items;
    }

    /**
     * @param \OpenApi\Annotations\Schema $responseSchema
     *
     * @return void
     */
    public static function addStatusProperty(Schema $responseSchema): void
    {
        $property = Util::getProperty($responseSchema, OpenApiField::STATUS);
        $property->type = 'string';
    }

    /**
     * @param \OpenApi\Annotations\Schema $responseSchema
     *
     * @return void
     */
    public static function addCodeProperty(Schema $responseSchema): void
    {
        $property = Util::getProperty($responseSchema, OpenApiField::CODE);
        $property->type = 'integer';
    }

    /**
     * @param \OpenApi\Annotations\Operation $operation
     * @param int $code
     * @param string $description
     * @param array<(\OpenApi\Annotations\MediaType|\OpenApi\Annotations\JsonContent|\OpenApi\Annotations\XmlContent|\OpenApi\Annotations\Attachable)> $content
     *
     * @return \OpenApi\Annotations\Response
     */
    public static function createResponse(Operation $operation, int $code, string $description, array $content): Response
    {
        /** @var \OpenApi\Annotations\Response $errorResponse */
        $errorResponse = Util::getIndexedCollectionItem($operation, Response::class, ['responses']);
        $errorResponse->response = $code;
        $errorResponse->description = $description;
        $errorResponse->content = $content;

        return $errorResponse;
    }
}

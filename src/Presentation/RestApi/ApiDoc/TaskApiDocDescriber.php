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
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Schema;

class TaskApiDocDescriber implements DescriberInterface
{
    /**
     * @var string
     */
    protected const JSON_TYPE = 'application/json';

    /**
     * @param \OpenApi\Annotations\OpenApi $api
     *
     * @return void
     */
    public function describe(OpenApi $api): void
    {
        $taskName = 'sdk-php-validate';
        $method = 'POST';

        $path = Util::getPath($api, sprintf('/api/v1/tasks/%s', $taskName));
        $operation = Util::getOperation($path, $method);
        $operation->tags = ['tasks'];

        /** @var \OpenApi\Annotations\RequestBody $requestBody */
        $requestBody = Util::getChild($operation, RequestBody::class);
        $requestBody->content = [static::JSON_TYPE => new MediaType(['mediaType' => static::JSON_TYPE])];

        /** @var \OpenApi\Annotations\Schema $schema */
        $schema = Util::getChild($requestBody->content[static::JSON_TYPE], Schema::class);

        $property = Util::getProperty($schema, 'project_dir');
        $property->type = 'string';
        $property->description = 'Project dir';

        $property = Util::getProperty($schema, 'checks');
        $property->type = 'array';

        /** @var \OpenApi\Annotations\Items $items */
        $items = Util::getChild($property, Items::class);
        $items->type = 'string';
        $property->items = $items;
    }
}
